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

        if ($flight_id && !in_array($flight_id, $_SESSION['offer_data'][$type])) {
            $_SESSION['offer_data'][$type][] = $flight_id;
        }

        $helper_cls = new TravelAlbania_Init_Helper();
        $price_per_person = $helper_cls->price_calculation($offer_id);
        $final_price = $helper_cls->price_calculation($offer_id, 'final');

        ob_start();
        $helper_cls->delete_btn($flight_id, $type);
        $delete_btn = ob_get_clean();

        ob_start();
        $helper_cls->render_summary();
        $summary_content = ob_get_clean();

        wp_send_json_success([
            'final_price' => $final_price,
            'price_per_person' => $price_per_person,
            'delete_btn' => $delete_btn,
            'summary_content' => $summary_content,
        ]);

        wp_die();
    }

    public function delete_flight_data_callback()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $offer_id = isset($_POST['offer_id']) ? intval($_POST['offer_id']) : 0;
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
        $price_per_person = $helper_cls->price_calculation($offer_id);
        $final_price = $helper_cls->price_calculation($offer_id, 'final');

        ob_start();
        $helper_cls->select_btn($flight_id, $type);
        $select_btn = ob_get_clean();

        ob_start();
        $helper_cls->render_summary();
        $summary_content = ob_get_clean();

        wp_send_json_success([
            'final_price' => $final_price,
            'price_per_person' => $price_per_person,
            'select_btn' => $select_btn,
            'summary_content' => $summary_content,
        ]);

        wp_die();
    }
}
