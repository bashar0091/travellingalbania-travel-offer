<?php
// Exit if accessed directly.
if (! defined('ABSPATH')) {
    exit;
}


class TravelAlbania_Ajax
{
    public function __construct()
    {
        add_action('wp_ajax_get_flight_data', [$this, 'get_flight_data_callback']);
        add_action('wp_ajax_nopriv_get_flight_data', [$this, 'get_flight_data_callback']);

        add_action('wp_ajax_delete_flight_data', [$this, 'delete_flight_data_callback']);
        add_action('wp_ajax_nopriv_delete_flight_data', [$this, 'delete_flight_data_callback']);
    }

    public function get_flight_data_callback()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $flight_id = isset($_POST['flight_id']) ? intval($_POST['flight_id']) : 0;
        $offer_id = isset($_POST['offer_id']) ? intval($_POST['offer_id']) : 0;
        $type      = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '';

        if (!isset($_SESSION['offer_data'])) {
            $_SESSION['offer_data'] = [
                'offer_id'   => $offer_id,
                'flights_id' => [],
                'accommodations_id' => [],
                'excursions_id' => [],
            ];
        }

        if ($flight_id && !in_array($flight_id, $_SESSION['offer_data'][$type])) {
            $_SESSION['offer_data'][$type][] = $flight_id;
        }

        $helper_cls = new TravelAlbania_Init_Helper();
        $total_price = $helper_cls->price_calculation();

        ob_start();
        $helper_cls->delete_btn($flight_id, $type);
        $delete_btn = ob_get_clean();

        wp_send_json_success([
            'total_price' => $total_price,
            'delete_btn' => $delete_btn,
        ]);

        wp_die();
    }

    public function delete_flight_data_callback()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $flight_id = isset($_POST['flight_id']) ? intval($_POST['flight_id']) : 0;
        $type      = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '';

        if (isset($_SESSION['offer_data'][$type]) && is_array($_SESSION['offer_data'][$type])) {
            $_SESSION['offer_data'][$type] = array_diff(
                $_SESSION['offer_data'][$type],
                [$flight_id]
            );

            $_SESSION['offer_data'][$type] = array_values($_SESSION['offer_data'][$type]);
        }

        $helper_cls = new TravelAlbania_Init_Helper();
        $total_price = $helper_cls->price_calculation();

        ob_start();
        $helper_cls->select_btn($flight_id, $type);
        $select_btn = ob_get_clean();

        wp_send_json_success([
            'total_price' => $total_price,
            'select_btn' => $select_btn,
        ]);

        wp_die();
    }
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
    // session_destroy();
}

// if (isset($_SESSION['offer_data'])) {
//     echo '<pre>';
//     print_r($_SESSION['offer_data']);
//     echo '</pre>';
// }
