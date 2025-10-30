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
        $post_id = isset($_POST['offer_id']) ? intval($_POST['offer_id']) : 0;
        $type      = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '';
        $key = isset($_POST['key']) ? intval($_POST['key']) : null;

        if ($flight_id && !in_array($flight_id, $_SESSION['offer_data_' . $post_id][$type])) {
            if ($type == 'flights_id' | $type == 'transports_id') {
                $_SESSION['offer_data_' . $post_id][$type][0] = $flight_id;
            } elseif ($type == 'accommodations_id') {
                $_SESSION['offer_data_' . $post_id][$type][$key][0] = $flight_id;
            } else {
                $_SESSION['offer_data_' . $post_id][$type][] = $flight_id;
            }
        }

        $helper_cls = new TravelAlbania_Init_Helper();
        $price_per_person = $helper_cls->price_calculation($post_id);
        $final_price = $helper_cls->price_calculation($post_id, 'final');

        ob_start();
        $helper_cls->delete_btn($flight_id, $type);
        $delete_btn = ob_get_clean();

        ob_start();
        $helper_cls->render_summary($post_id);
        $summary_content = ob_get_clean();

        ob_start();
        if ($type == 'flights_id') {
            render_flight_content($post_id, $helper_cls, [$flight_id]);
        } elseif ($type == 'accommodations_id') {
            $session_offer_data = array();
            if (isset($_SESSION['offer_data_' . $post_id])) {
                $session_offer_data = $_SESSION['offer_data_' . $post_id];
            }
            $session_accommodations_id = isset($session_offer_data['accommodations_id']) && is_array($session_offer_data['accommodations_id'])
                ? $session_offer_data['accommodations_id']
                : [];

            render_accommodations_content($post_id, $helper_cls, $session_accommodations_id);
        } elseif ($type == 'transports_id') {
            render_transport_content($post_id, $helper_cls, [$flight_id]);
        }
        $render_flight_content = ob_get_clean();

        wp_send_json_success([
            'final_price' => $final_price,
            'price_per_person' => $price_per_person,
            'delete_btn' => $delete_btn,
            'summary_content' => $summary_content,
            'render_flight_content' => $render_flight_content,
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

        if (isset($_SESSION['offer_data_' . $offer_id][$type]) && is_array($_SESSION['offer_data_' . $offer_id][$type])) {
            $_SESSION['offer_data_' . $offer_id][$type] = array_diff(
                $_SESSION['offer_data_' . $offer_id][$type],
                [$flight_id]
            );

            $_SESSION['offer_data_' . $offer_id][$type] = array_values($_SESSION['offer_data_' . $offer_id][$type]);
        }

        $helper_cls = new TravelAlbania_Init_Helper();
        $price_per_person = $helper_cls->price_calculation($offer_id);
        $final_price = $helper_cls->price_calculation($offer_id, 'final');

        ob_start();
        $helper_cls->select_btn($flight_id, $type);
        $select_btn = ob_get_clean();

        ob_start();
        $helper_cls->render_summary($offer_id);
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
