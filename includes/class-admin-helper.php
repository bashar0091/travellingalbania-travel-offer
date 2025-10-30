<?php
// Exit if accessed directly.
if (! defined('ABSPATH')) {
    exit;
}


class TravelAlbania_Admin_Helper
{
    public function __construct()
    {
        add_action('init', [$this, 'post_type']);
        add_action('init', [$this, 'taxonomy_register']);

        // post.php , category order fix
        add_filter('wp_terms_checklist_args', [$this, 'checklist_args']);

        add_action('admin_bar_menu', [$this, 'travel_offer_toolbar_link'], 999);
        add_action('init', [$this, 'clean_offer_session_action']);
    }

    // post.php , category order fix
    public function checklist_args($args)
    {
        $args['checked_ontop'] = false;
        return $args;
    }

    public function travel_offer_toolbar_link($wp_admin_bar)
    {
        if (!is_user_logged_in()) {
            return;
        }

        $current_url  = (is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        $nonce = wp_create_nonce('clean_offer_session');

        $href = add_query_arg(array(
            'clean_offer_session' => '1',
            '_wpnonce' => $nonce,
            'redirect_to' => urlencode($current_url)
        ), home_url('/'));

        $args = array(
            'id'    => 'clean_offer_session',
            'title' => '🧹 Clean Offer Session',
            'href'  => $href,
            'meta'  => array('title' => 'Click to clear offer session')
        );

        $wp_admin_bar->add_node($args);
    }


    public function clean_offer_session_action()
    {
        if (isset($_GET['clean_offer_session']) && $_GET['clean_offer_session'] == '1') {
            if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'clean_offer_session')) {
                wp_die('Security check failed.');
            }

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            session_destroy();

            $redirect_to = isset($_GET['redirect_to']) ? esc_url_raw($_GET['redirect_to']) : admin_url();

            wp_safe_redirect($redirect_to);
            exit;
        }
    }


    public function post_type()
    {
        $labels = array(
            'name'                  => _x('Travel Offers', 'tta-travel-offer'),
            'singular_name'         => _x('Travel Offer', 'tta-travel-offer'),
            'menu_name'             => _x('Travel Offers', 'tta-travel-offer'),
            'name_admin_bar'        => _x('Travel Offer', 'tta-travel-offer'),
            'add_new'               => __('Add New', 'tta-travel-offer'),
            'add_new_item'          => __('Add New Travel Offer', 'tta-travel-offer'),
            'edit_item'             => __('Edit Travel Offer', 'tta-travel-offer'),
            'new_item'              => __('New Travel Offer', 'tta-travel-offer'),
            'view_item'             => __('View Travel Offer', 'tta-travel-offer'),
            'search_items'          => __('Search Travel Offers', 'tta-travel-offer'),
            'not_found'             => __('No Travel Offers found', 'tta-travel-offer'),
            'not_found_in_trash'    => __('No Travel Offers found in Trash', 'tta-travel-offer'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'travel-offer'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 5,
            'supports'           => array('title', 'thumbnail'),
            'show_in_rest'       => false,
            'menu_icon'          => 'dashicons-palmtree',
        );

        register_post_type('tta_travel_offer', $args);
    }

    public function taxonomy_register()
    {
        $labels = [
            'name'              => _x('Flights', 'tta-travel-offer'),
            'singular_name'     => _x('Flight', 'tta-travel-offer'),
            'search_items'      => __('Search Flights', 'tta-travel-offer'),
            'all_items'         => __('All Flights', 'tta-travel-offer'),
            'parent_item'       => __('Parent Flight', 'tta-travel-offer'),
            'parent_item_colon' => __('Parent Flight:', 'tta-travel-offer'),
            'edit_item'         => __('Edit Flight', 'tta-travel-offer'),
            'update_item'       => __('Update Flight', 'tta-travel-offer'),
            'add_new_item'      => __('Add New Flight', 'tta-travel-offer'),
            'new_item_name'     => __('New Flight Name', 'tta-travel-offer'),
            'menu_name'         => __('Flights', 'tta-travel-offer'),
        ];
        $args = [
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'query_var'         => true,
            'show_admin_column' => false,
            'meta_box_cb'       => false,
            'show_in_quick_edit' => false,
            // 'rewrite'           => ['slug' => 'travel-flights'],
            'rewrite'           => false,
            'public'            => false,
        ];
        register_taxonomy('tta_travel_flights', ['tta_travel_offer'], $args);


        //=====================
        $labels = [
            'name'              => _x('Flight Includes', 'tta-travel-offer'),
            'singular_name'     => _x('Flight Include', 'tta-travel-offer'),
            'search_items'      => __('Search Flight Includes', 'tta-travel-offer'),
            'all_items'         => __('All Flight Includes', 'tta-travel-offer'),
            'parent_item'       => __('Parent Flight Include', 'tta-travel-offer'),
            'parent_item_colon' => __('Parent Flight Include:', 'tta-travel-offer'),
            'edit_item'         => __('Edit Flight Include', 'tta-travel-offer'),
            'update_item'       => __('Update Flight Include', 'tta-travel-offer'),
            'add_new_item'      => __('Add New Flight Include', 'tta-travel-offer'),
            'new_item_name'     => __('New Flight Include Name', 'tta-travel-offer'),
            'menu_name'         => __('Flight Includes', 'tta-travel-offer'),
        ];
        $args = [
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'query_var'         => true,
            'show_admin_column' => false,
            'meta_box_cb'       => false,
            'show_in_quick_edit' => false,
            // 'rewrite'           => ['slug' => 'travel-flight-includes'],
            'rewrite'           => false,
            'public'            => false,
        ];
        register_taxonomy('tta_travel_flight_includes', ['tta_travel_offer'], $args);


        //=====================
        $labels = [
            'name'              => _x('Accommodations', 'tta-travel-offer'),
            'singular_name'     => _x('Accommodation', 'tta-travel-offer'),
            'search_items'      => __('Search Accommodations', 'tta-travel-offer'),
            'all_items'         => __('All Accommodations', 'tta-travel-offer'),
            'parent_item'       => __('Parent Accommodation', 'tta-travel-offer'),
            'parent_item_colon' => __('Parent Accommodation:', 'tta-travel-offer'),
            'edit_item'         => __('Edit Accommodation', 'tta-travel-offer'),
            'update_item'       => __('Update Accommodation', 'tta-travel-offer'),
            'add_new_item'      => __('Add New Accommodation', 'tta-travel-offer'),
            'new_item_name'     => __('New Accommodation Name', 'tta-travel-offer'),
            'menu_name'         => __('Accommodations', 'tta-travel-offer'),
        ];
        $args = [
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'query_var'         => true,
            'show_admin_column' => false,
            'meta_box_cb'       => false,
            'show_in_quick_edit' => false,
            // 'rewrite'           => ['slug' => 'travel-accommodations'],
            'rewrite'           => false,
            'public'            => false,
        ];
        register_taxonomy('tta_travel_accommodations', ['tta_travel_offer'], $args);


        //=====================
        $labels = [
            'name'              => _x('Excursions', 'tta-travel-offer'),
            'singular_name'     => _x('Excursion', 'tta-travel-offer'),
            'search_items'      => __('Search Excursions', 'tta-travel-offer'),
            'all_items'         => __('All Excursions', 'tta-travel-offer'),
            'parent_item'       => __('Parent Excursion', 'tta-travel-offer'),
            'parent_item_colon' => __('Parent Excursion:', 'tta-travel-offer'),
            'edit_item'         => __('Edit Excursion', 'tta-travel-offer'),
            'update_item'       => __('Update Excursion', 'tta-travel-offer'),
            'add_new_item'      => __('Add New Excursion', 'tta-travel-offer'),
            'new_item_name'     => __('New Excursion Name', 'tta-travel-offer'),
            'menu_name'         => __('Excursions', 'tta-travel-offer'),
        ];
        $args = [
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => false,
            'meta_box_cb'       => false,
            'show_in_quick_edit' => false,
            'query_var'         => true,
            // 'rewrite'           => ['slug' => 'travel-excursions'],
            'rewrite'           => false,
            'public'            => false,
        ];
        register_taxonomy('tta_travel_excursions', ['tta_travel_offer'], $args);


        //=====================
        $labels = [
            'name'              => _x('Transports', 'tta-travel-offer'),
            'singular_name'     => _x('Transport', 'tta-travel-offer'),
            'search_items'      => __('Search Transports', 'tta-travel-offer'),
            'all_items'         => __('All Transports', 'tta-travel-offer'),
            'parent_item'       => __('Parent Transport', 'tta-travel-offer'),
            'parent_item_colon' => __('Parent Transport:', 'tta-travel-offer'),
            'edit_item'         => __('Edit Transport', 'tta-travel-offer'),
            'update_item'       => __('Update Transport', 'tta-travel-offer'),
            'add_new_item'      => __('Add New Transport', 'tta-travel-offer'),
            'new_item_name'     => __('New Transport Name', 'tta-travel-offer'),
            'menu_name'         => __('Transports', 'tta-travel-offer'),
        ];
        $args = [
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => false,
            'meta_box_cb'       => false,
            'show_in_quick_edit' => false,
            'query_var'         => true,
            // 'rewrite'           => ['slug' => 'travel-transports'],
            'rewrite'           => false,
            'public'            => false,
        ];
        register_taxonomy('tta_travel_transports', ['tta_travel_offer'], $args);


        //=====================
        $labels = [
            'name'              => _x('Extra Cost', 'tta-travel-offer'),
            'singular_name'     => _x('Extra Cost', 'tta-travel-offer'),
            'search_items'      => __('Search Extra Cost', 'tta-travel-offer'),
            'all_items'         => __('All Extra Cost', 'tta-travel-offer'),
            'parent_item'       => __('Parent Extra Cost', 'tta-travel-offer'),
            'parent_item_colon' => __('Parent Extra Cost:', 'tta-travel-offer'),
            'edit_item'         => __('Edit Extra Cost', 'tta-travel-offer'),
            'update_item'       => __('Update Extra Cost', 'tta-travel-offer'),
            'add_new_item'      => __('Add New Extra Cost', 'tta-travel-offer'),
            'new_item_name'     => __('New Extra Cost Name', 'tta-travel-offer'),
            'menu_name'         => __('Extra Cost', 'tta-travel-offer'),
        ];
        $args = [
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => false,
            'meta_box_cb'       => false,
            'show_in_quick_edit' => false,
            'query_var'         => true,
            // 'rewrite'           => ['slug' => 'travel-extra-cost'],
            'rewrite'           => false,
            'public'            => false,
        ];
        register_taxonomy('tta_travel_extra_cost', ['tta_travel_offer'], $args);
    }
}
