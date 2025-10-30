<?php

/**
 * Plugin Name: TravellingAlbania Travel Offer
 * Plugin URI: 
 * Description: Manage and display travel offers, bookings, and tours in Albania. Fully compatible with WordPress.
 * Version: 1.0.0
 * Author: 
 * Author URI: 
 * License: GPL2
 * Text Domain: tta-travel-offer
 */

// Exit if accessed directly.
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Define Plugin Constants
 */
define('TravelAlbania_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('TravelAlbania_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Core Plugin Class Includes
 */
require_once TravelAlbania_PLUGIN_PATH . 'includes/class-admin-helper.php';
require_once TravelAlbania_PLUGIN_PATH . 'includes/class-init-helper.php';
require_once TravelAlbania_PLUGIN_PATH . 'includes/class-ajax.php';
require_once TravelAlbania_PLUGIN_PATH . 'includes/meta-box.php';

// require template function 
require_once TravelAlbania_PLUGIN_PATH . 'templates/travel-offer-part/offer-part-function/flights-func.php';
require_once TravelAlbania_PLUGIN_PATH . 'templates/travel-offer-part/offer-part-function/accommodations-func.php';
require_once TravelAlbania_PLUGIN_PATH . 'templates/travel-offer-part/offer-part-function/transports-func.php';

class TravelAlbania_Travel_Offer
{
    /**
     * Constructor
     */
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);

        // Initialize Classes
        new TravelAlbania_Admin_Helper();
        new TravelAlbania_Init_Helper();
        new TravelAlbania_Ajax();
    }

    /**
     * Enqueue Scripts and Styles
     */
    public function enqueue_assets($hook)
    {
        // Enqueue Styles

        // Enqueue Scripts
        wp_enqueue_script(
            'tta-tailwind-script',
            'https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4',
            array(),
            '1.0.0'
        );

        if (is_singular('tta_travel_offer')) {
            wp_enqueue_style(
                'tta-custom-style',
                TravelAlbania_PLUGIN_URL . 'assets//css/custom.css',
                array(),
                '1.0.0'
            );
        }


        wp_enqueue_script(
            'tta-custom-script',
            TravelAlbania_PLUGIN_URL . 'assets/js/custom.js',
            array('jquery'),
            '1.0.0',
            true
        );

        wp_localize_script(
            'tta-custom-script',
            'local',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
            )
        );
    }
}

// Initialize the plugin
new TravelAlbania_Travel_Offer();


// add_action('init', function () {
//     $post_id = 131;
//     $key = 'accommodations_id';

//     if (session_status() === PHP_SESSION_NONE) {
//         session_start();
//     }

//     $session_offer_data = isset($_SESSION['offer_data_' . $post_id]) ? $_SESSION['offer_data_' . $post_id] : [];
//     $session_data = $session_offer_data[$key];

//     $select_total_price = 0;

//     if (!empty($session_data) && is_array($session_data)) {
//         foreach ($session_data as $term_id) {



//             if (empty($term_id)) {
//                 return  $select_total_price;
//             }



//             $term = get_term($term_id[0]);



//             $price = 0;
//             if ($term->taxonomy == 'tta_travel_accommodations') {

//                 for ($i = 1; $i <= 4; $i++) {
//                     $season_price = get_term_meta($term_id[0], "price_season_$i", true);
//                     if ($season_price) {
//                         echo '<pre>';
//                         print_r($term);
//                         echo '</pre>';
//                         $price = $season_price;
//                         break;
//                     }
//                 }
//             } else {
//                 $price = (float) get_term_meta($term_id, 'price', true);
//             }

//             $select_total_price += $price;
//         }
//     }

//     echo $select_total_price;
// });
