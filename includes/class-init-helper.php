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
        add_action('template_redirect', [$this, 'travel_offer_booking_submti']);
        add_action('woocommerce_before_calculate_totals', [$this, 'set_price_for_offer_product'], 10, 1);
    }

    public function travel_offer_booking_submti()
    {
        if (isset($_POST['offer_booking_submit'])) {
            $offer_product_id = isset($_POST['offer_product_id']) ? intval($_POST['offer_product_id']) : 0;
            $total_offer_price = $this->price_calculation();

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

    public function delete_btn($id, $type)
    {
?>
        <div class="flex items-center gap-5">
            <p class="text-green-800 !font-bold !mb-0">Choosen</p>
            <button type="button" class="flight_on_delete !bg-red-600 !p-2" data-type="<?php echo esc_attr($type); ?>" data-flightid="<?php echo esc_attr($id); ?>">Delete</button>
        </div>
    <?php
    }

    public function select_btn($id, $type)
    {
    ?>
        <button type="button" class="flight_on_select" data-type="<?php echo esc_attr($type); ?>" data-flightid="<?php echo esc_attr($id); ?>">Select</button>
        <?php
    }

    public function price_calculation()
    {
        global $wpdb;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $session_offer_data = isset($_SESSION['offer_data']) ? $_SESSION['offer_data'] : [];

        $select_total_price = 0;
        $included_total_price = 0;

        $price_keys = ['flights_id', 'accommodations_id', 'excursions_id', 'transports_id'];

        foreach ($price_keys as $key) {
            if (!empty($session_offer_data[$key]) && is_array($session_offer_data[$key])) {
                foreach ($session_offer_data[$key] as $term_id) {
                    $price = (float) get_term_meta($term_id, 'price', true);
                    $select_total_price += $price;
                }
            }
        }

        $meta_key = 'is_package_included';
        $meta_value = 'yes';
        $term_ids = $wpdb->get_col($wpdb->prepare(
            "SELECT term_id FROM {$wpdb->termmeta} WHERE meta_key = %s AND meta_value = %s",
            $meta_key,
            $meta_value
        ));

        foreach ($term_ids as $term_id) {
            $price = (float) get_term_meta($term_id, 'price', true);
            $included_total_price += $price;
        }

        return $select_total_price + $included_total_price;
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
                    <h4 class="!mb-3"><?= esc_html($label) ?></h4>
                    <?php foreach ($combined_ids as $term_id):
                        $term = get_term($term_id, $data['taxonomy']);
                        $title = (!is_wp_error($term) && $term) ? $term->name : 'Unknown';
                    ?>
                        <div class="shadow-md p-3 mb-3"><?= esc_html($title) ?></div>
                    <?php endforeach; ?>
                </div>
<?php
            endif;
        endforeach;
    }
}
