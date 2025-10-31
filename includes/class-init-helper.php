<?php
// Exit if accessed directly.

use ElementorPro\Modules\Woocommerce\Widgets\Add_To_Cart;

if (! defined('ABSPATH')) {
	exit;
}


class TravelAlbania_Init_Helper
{
	public function __construct()
	{
		add_filter('template_include', [$this, 'travel_offer_single_template']);
		add_action('template_redirect', [$this, 'travel_offer_booking_submit']);
		add_action('woocommerce_before_calculate_totals', [$this, 'set_price_for_offer_product'], 10, 1);
	}

	public function travel_offer_booking_submit()
	{
		if (isset($_POST['offer_booking_submit'])) {
			$offer_id = isset($_POST['offer_id']) ? intval($_POST['offer_id']) : 0;
			$offer_product_id = isset($_POST['offer_product_id']) ? intval($_POST['offer_product_id']) : 0;
			$total_offer_price = $this->price_calculation($offer_id, 'final');

			if (class_exists('WC_Cart') && $offer_product_id > 0 && $total_offer_price > 0) {
				if (!WC()->cart) {
					wc_load_cart();
				}

				WC()->cart->empty_cart();

				$added = WC()->cart->add_to_cart($offer_product_id, 1, 0, [], [
					'offer_total_price' => $total_offer_price,
				]);

				if ($added) {
					wp_safe_redirect(wc_get_checkout_url());
					exit;
				}
			}
		}
	}

	public function set_price_for_offer_product($cart)
	{
		if (is_admin() && !defined('DOING_AJAX')) return;

		foreach ($cart->get_cart() as $cart_item) {
			if (isset($cart_item['offer_total_price'])) {
				$cart_item['data']->set_price($cart_item['offer_total_price']);
			}
		}
	}

	public function travel_offer_single_template($template)
	{
		if (is_singular('tta_travel_offer')) {
			$plugin_template = TravelAlbania_PLUGIN_PATH . 'templates/single-travel-offer.php';
			if (file_exists($plugin_template)) {
				return $plugin_template;
			}
		}
		return $template;
	}

	public function delete_btn($id, $type, $label = 'Choosen', $method = '')
	{
?>
<div class="<?php echo $method != 'not_delete' ? 'flight_on_delete' : ''; ?> flex items-center gap-5 border border-green-800 px-[30px] py-[10px] rounded-sm cursor-pointer hover:!bg-[#e73017] transition duration-200" data-type="<?php echo esc_attr($type); ?>" data-flightid="<?php echo esc_attr($id); ?>">
	<p class="text-green-800 !mb-0 transition duration-200"><?php echo wp_kses_post($label); ?></p>
	<?php if ($method != 'not_delete'): ?>
	<i class="fa fa-trash text-[#e73017] cursor-pointer !p-0 !border-none transition duration-200"></i>
	<?php endif; ?>
</div>
<?php
	}

	public function select_btn($id, $type, $key = null)
	{
?>
<button type="button" class="flight_on_select" <?php echo (isset($key) ? 'data-key="' . esc_attr($key) . '"' : ''); ?> data-type="<?php echo esc_attr($type); ?>" data-flightid="<?php echo esc_attr($id); ?>">Select</button>
<?php
	}

	public function pick_price_from_session($post_id, $key)
	{
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}

		$session_offer_data = isset($_SESSION['offer_data_' . $post_id]) ? $_SESSION['offer_data_' . $post_id] : [];
		$session_data = $session_offer_data[$key];

		$select_total_price = 0;

		if (!empty($session_data) && is_array($session_data)) {
			foreach ($session_data as $term_id) {

				if (empty($term_id)) {
					return  $select_total_price;
				}

				$term = get_term($term_id);

				if ($key == 'accommodations_id') {
					$term = get_term($term_id[0]);
				}
				$price = 0;
				if ($term->taxonomy == 'tta_travel_accommodations') {
					for ($i = 1; $i <= 4; $i++) {
						$season_price = get_term_meta($term_id[0], "price_season_$i", true);
						if ($season_price) {
							$price = $season_price;
							break;
						}
					}
				} else {
					$price = (float) get_term_meta($term_id, 'price', true);
				}

				$select_total_price += $price;
			}
		}

		return $select_total_price;
	}

	public function price_calculation($postid, $type = null)
	{
		$people_count = (float) get_post_meta($postid, 'number_of_people', true);

		$accommodation_price = $this->pick_price_from_session($postid, 'accommodations_id') / $people_count;

		$transport_price = $this->pick_price_from_session($postid, 'transports_id') / $people_count;

		$extra_cost = $this->find_price_with_meta_common($postid, 'extra_cost_select');

		$price_per_person =
			$this->pick_price_from_session($postid, 'excursions_id')
			+
			$this->pick_price_from_session($postid, 'flights_id')
			+
			$this->find_price_with_terms($postid)
			+
			$accommodation_price
			+
			$transport_price
			+
			$this->find_price_with_meta($postid, 'TravelAlbania_excursions_repeat', 'excursion_select');

		if ($type == 'final') {
			return number_format((float)($price_per_person * $people_count) + $extra_cost, 2, '.', '');
		} else {
			return number_format((float)($price_per_person), 2, '.', '');
		}
	}

	public function find_termid_with_meta($post_id, $meta_name, $array_name)
	{
		$term_ids = array();
		$post_repeater_meta = get_post_meta($post_id, $meta_name, true);
		foreach ($post_repeater_meta as $data) {
			$term_id_get = $data[$array_name];
			if(!empty($term_id_get)) {
				foreach ($term_id_get as $id) {
					$is_included = get_term_meta($id, 'is_package_included', true);
					if ($is_included == 'yes') {
						$term_ids[] = $id;
					}
				}
			}  
		}
		return $term_ids;
	}

	public function find_group_termid_with_meta($post_id, $meta_name, $array_name)
	{
		$term_ids = array();
		$post_repeater_meta = get_post_meta($post_id, $meta_name, true);

		if (!is_array($post_repeater_meta)) {
			return $term_ids;
		}

		foreach ($post_repeater_meta as $key => $data) {
			if (empty($data[$array_name]) || !is_array($data[$array_name])) {
				continue;
			}

			foreach ($data[$array_name] as $id) {
				$is_included = get_term_meta($id, 'is_package_included', true);
				if ($is_included === 'yes') {
					$term_ids[$key][] = $id;
				}
			}
		}

		return $term_ids;
	}


	public function find_termid_with_meta_select($post_id, $meta_name)
	{
		$term_ids = array();
		$termids = get_post_meta($post_id, $meta_name, true);
		if (!empty($termids)) {
			foreach ($termids as $id) {
				$is_included = get_term_meta($id, 'is_package_included', true);
				if ($is_included == 'yes') {
					$term_ids[] = $id;
				}
			}
		}
		return $term_ids;
	}

	public function find_price_with_meta($post_id, $meta_name, $array_name)
	{
		$price = 0;
		$post_repeater_meta = get_post_meta($post_id, $meta_name, true);
		foreach ($post_repeater_meta as $data) {
			$term_id_get = $data[$array_name];
			foreach ($term_id_get as $id) {
				$is_included = get_term_meta($id, 'is_package_included', true);
				if ($is_included == 'yes') {
					$price += (float) get_term_meta($id, 'price', true);
				}
			}
		}
		return $price;
	}

	public function find_price_with_meta_common($post_id, $meta_name)
	{
		$price = 0;
		$post_repeater_meta = get_post_meta($post_id, $meta_name, true);
		if (!empty($post_repeater_meta)) {
			foreach ($post_repeater_meta as $id) {
				$price += (float) get_term_meta($id, 'price', true);
			}
		}
		return $price;
	}

	public function find_price_with_terms($post_id)
	{
		$price = 0;
		$terms = wp_get_post_terms($post_id, 'tta_travel_flights', array('fields' => 'ids'));

		if (empty($terms) || is_wp_error($terms)) {
			return 0;
		}

		foreach ($terms as $id) {
			$is_included = get_term_meta($id, 'is_package_included', true);
			if ($is_included == 'yes') {
				$price += (float) get_term_meta($id, 'price', true);
			}
		}

		return $price;
	}

	public function showing_price_different($post_id, $type, $termid, $key = null)
	{
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}

		$session_offer_data = $_SESSION['offer_data_' . $post_id] ?? [];
		$session_flights_id = $session_offer_data[$type] ?? [];
		$flights_id = $session_flights_id[0] ?? null;
		if ($type == 'accommodations_id') {
			$flights_id = $session_flights_id[$key][0] ?? null;
		}

		if (!$flights_id) {
			return 0;
		}

		$term = get_term($flights_id);

		$choosen_price = 0;
		$all_price = 0;

		if ($term->taxonomy == 'tta_travel_accommodations') {
			for ($i = 1; $i <= 4; $i++) {
				$season_price = get_term_meta($flights_id, "price_season_$i", true);
				if ($season_price) {
					$choosen_price = $season_price;
					break;
				}
			}

			for ($i = 1; $i <= 4; $i++) {
				$season_price = get_term_meta($termid, "price_season_$i", true);
				if ($season_price) {
					$all_price = $season_price;
					break;
				}
			}
		} else {
			$choosen_price = (float) get_term_meta($flights_id, 'price', true);
			$all_price = (float) get_term_meta($termid, 'price', true);
		}

		$difference = $all_price - $choosen_price;

		$formatted_difference = ($difference > 0 ? '+' : '') . number_format($difference, 2);

		return $formatted_difference;
	}


	public function render_summary($post_id)
	{
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}

		$session_offer_data = isset($_SESSION['offer_data_' . $post_id]) ? $_SESSION['offer_data_' . $post_id] : [];

		$session_flights_id = isset($session_offer_data['flights_id']) && is_array($session_offer_data['flights_id'])
			? $session_offer_data['flights_id']
			: [];

		$session_accommodations_id = isset($session_offer_data['accommodations_id']) && is_array($session_offer_data['accommodations_id'])
			? $session_offer_data['accommodations_id'][0]
			: [];

		$session_excursions_id = isset($session_offer_data['excursions_id']) && is_array($session_offer_data['excursions_id'])
			? $session_offer_data['excursions_id']
			: [];

		$session_transports_id = isset($session_offer_data['transports_id']) && is_array($session_offer_data['transports_id'])
			? $session_offer_data['transports_id']
			: [];

		$sections = [
			'Flights' => [
				'ids' => $session_flights_id,
				'taxonomy' => 'tta_travel_flights',
			],
			'Accommodations' => [
				'ids' => $session_accommodations_id,
				'taxonomy' => 'tta_travel_accommodations',
			],
			'Excursions' => [
				'ids' => $session_excursions_id,
				'taxonomy' => 'tta_travel_excursions',
			],
			'Transports' => [
				'ids' => $session_transports_id,
				'taxonomy' => 'tta_travel_transports',
			],
		];

		foreach ($sections as $label => $data):
		$filtered_term_ids = get_terms([
			'taxonomy' => $data['taxonomy'],
			'hide_empty' => false,
			'meta_query' => [
				[
					'key' => 'is_package_included',
					'value' => 'yes',
					'compare' => '=',
				]
			],
			'fields' => 'ids',
		]);

		$combined_ids = array_unique(array_merge((array) $data['ids'], (array) $filtered_term_ids));

		if (!empty($combined_ids)):
?>
<div class="mt-10">
	<h4 class="!mb-3 !text-lg"><?= esc_html($label) ?></h4>
	<?php foreach ($combined_ids as $term_id):
		$term = get_term($term_id, $data['taxonomy']);
		$title = (!is_wp_error($term) && $term) ? $term->name : 'Unknown';
	?>
	<div class="flex items-center gap-2 shadow-md p-3 mb-3 rounded-lg ring ring-[#80808012] shadow-m">
		<i class="fa fa-check-circle text-green-800" aria-hidden="true"></i>
		<div><?= esc_html($title) ?></div>
	</div>
	<?php endforeach; ?>
</div>
<?php
		endif;
		endforeach;
	}
}
