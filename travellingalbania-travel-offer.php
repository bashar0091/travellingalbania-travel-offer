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
