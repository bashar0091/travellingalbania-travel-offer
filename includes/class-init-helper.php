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

    public function delete_btn($id, $type, $label = 'Choosen')
    {
?>
        <div class="flight_on_delete flex items-center gap-5 border border-green-800 px-[30px] py-[10px] rounded-sm cursor-pointer hover:!bg-[#e73017] transition duration-200" data-type="<?php echo esc_attr($type); ?>" data-flightid="<?php echo esc_attr($id); ?>">
            <p class="text-green-800 !mb-0 transition duration-200"><?php echo wp_kses_post($label); ?></p>
            <i class="fa fa-trash text-[#e73017] cursor-pointer !p-0 !border-none transition duration-200"></i>
        </div>
    <?php
    }

    public function select_btn($id, $type)
    {
    ?>
        <button type="button" class="flight_on_select" data-type="<?php echo esc_attr($type); ?>" data-flightid="<?php echo esc_attr($id); ?>">Select</button>
        <?php
    }

    public function pick_price_from_session($key)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $session_offer_data = isset($_SESSION['offer_data']) ? $_SESSION['offer_data'] : [];

        $select_total_price = 0;

        if (!empty($session_offer_data[$key]) && is_array($session_offer_data[$key])) {
            foreach ($session_offer_data[$key] as $term_id) {
                $price = (float) get_term_meta($term_id, 'price', true);
                $select_total_price += $price;
            }
        }

        return $select_total_price;
    }

    public function price_calculation($postid, $type = null)
    {
        $people_count = (float) get_post_meta($postid, 'number_of_people', true);

        $accommodation_price = $this->pick_price_from_session('accommodations_id') / $people_count;

        $transport_price = $this->pick_price_from_session('transports_id') / $people_count;

        $price_per_person =
            $this->pick_price_from_session('excursions_id')
            +
            $this->pick_price_from_session('flights_id')
            +
            $this->find_price_with_terms($postid)
            +
            $accommodation_price
            +
            $transport_price
            +
            $this->find_price_with_meta($postid, 'TravelAlbania_excursions_repeat', 'excursion_select');

        if ($type == 'final') {
            return number_format((float)$price_per_person * $people_count, 2, '.', '');
        } else {
            return number_format((float)$price_per_person, 2, '.', '');
        }
    }

    public function find_termid_with_meta($post_id, $meta_name, $array_name)
    {
        $term_ids = array();
        $post_repeater_meta = get_post_meta($post_id, $meta_name, true);
        foreach ($post_repeater_meta as $data) {
            $term_id_get = $data[$array_name];
            foreach ($term_id_get as $id) {
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

    public function render_summary()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $session_offer_data = isset($_SESSION['offer_data']) ? $_SESSION['offer_data'] : [];

        $session_flights_id = isset($session_offer_data['flights_id']) && is_array($session_offer_data['flights_id'])
            ? $session_offer_data['flights_id']
            : [];

        $session_accommodations_id = isset($session_offer_data['accommodations_id']) && is_array($session_offer_data['accommodations_id'])
            ? $session_offer_data['accommodations_id']
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

            $combined_ids = array_unique(array_merge($data['ids'], $filtered_term_ids));

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
