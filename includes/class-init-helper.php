<?php
// Exit if accessed directly.
if (! defined('ABSPATH')) {
    exit;
}


class TravelAlbania_Init_Helper
{
    public function __construct()
    {
        add_filter('template_include', [$this, 'travel_offer_single_template']);
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
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $session_offer_data = isset($_SESSION['offer_data']) ? $_SESSION['offer_data'] : [];

        $total_price = 0;

        $price_keys = ['flights_id', 'accommodations_id', 'excursions_id', 'transports_id'];

        foreach ($price_keys as $key) {
            if (!empty($session_offer_data[$key]) && is_array($session_offer_data[$key])) {
                foreach ($session_offer_data[$key] as $term_id) {
                    $price = (float) get_term_meta($term_id, 'price', true);
                    $total_price += $price;
                }
            }
        }

        return $total_price;
    }

    public function render_summary()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $session_offer_data = array();
        if (isset($_SESSION['offer_data'])) {
            $session_offer_data = $_SESSION['offer_data'];
        }

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
            if (!empty($data['ids'])):
        ?>
                <div class="mt-10">
                    <h4 class="!mb-3"><?= esc_html($label) ?></h4>
                    <?php
                    foreach ($data['ids'] as $term_id):
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
