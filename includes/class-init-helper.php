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

    function travel_offer_single_template($template)
    {
        if (is_singular('tta_travel_offer')) {
            $plugin_template = TravelAlbania_PLUGIN_PATH . 'templates/single-travel-offer.php';
            if (file_exists($plugin_template)) {
                return $plugin_template;
            }
        }
        return $template;
    }
}
