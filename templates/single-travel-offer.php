<?php
get_header();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$post_id = get_the_ID();
$helper_cls = new TravelAlbania_Init_Helper();

if (!isset($_SESSION['offer_data'])) {
    $_SESSION['offer_data'] = [
        'offer_id'   => $post_id,
        'flights_id' => [],
        'accommodations_id' => $helper_cls->find_termid_with_meta($post_id, 'TravelAlbania_accommodations_repeat', 'accommodation_select'),
        'excursions_id' => [],
        'transports_id' => $helper_cls->find_termid_with_meta($post_id, 'TravelAlbania_transports_repeat', 'transport_select'),
    ];
}

// session_destroy();
// if (isset($_SESSION['offer_data'])) {
//     echo '<pre>';
//     print_r($_SESSION['offer_data']);
//     echo '</pre>';
// }


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

$menu_items = [
    'Program' => 'fa fa-briefcase',
    'Flights' => 'fa fa-plane',
    'Accommodations' => 'fa fa-building',
    'Excursions' => 'fa fa-compass',
    'Transport' => 'fa fa-car',
    'Extras' => 'fa fa-car',
    'Summary' => 'fa fa-file-text',
    'Book' => 'fa fa-calendar'
];

$active_tab = isset($_GET['active']) ? sanitize_text_field($_GET['active']) : 'program';

$offer_product_id = get_post_meta($post_id, 'connect_woocommerce', true);

$logo =  plugin_dir_url(__FILE__) . "../assets/img/black-logo.webp";

$people = get_post_meta($post_id, 'number_of_people', true);
?>

<style>
    .offer_tab_top {
        position: relative;
        z-index: 1;
    }

    .offer_tab_top.active {
        background: var(--custom_main_color);
        color: #fff;
        z-index: 2;
    }

    .offer_tab_top.active::after {
        content: "";
        position: absolute;
        top: 0;
        right: -20px;
        width: 22px;
        height: 100%;
        background: var(--custom_main_color);
        clip-path: polygon(10% 0, 100% 50%, 10% 98%, 0% 100%, 0 52%, 0% 0%);
        z-index: 3;
    }

    .offer_tab_top.active::before {
        content: "";
        position: absolute;
        top: 0;
        left: -1px;
        width: 22px;
        height: 100%;
        clip-path: polygon(100% 50%, 0 0, 0 100%);
        border-top-left-radius: 6px;
        border-bottom-left-radius: 6px;
        background: #fff;
        z-index: 4;
    }

    .offer_tab_top i {
        visibility: hidden;
    }

    .offer_tab_top.active i {
        display: inline-block;
        visibility: visible;
        padding-left: 20px;
    }
</style>

<form class="w-full" method="POST">
    <input type="hidden" name="offer_id" value="<?php echo esc_attr($post_id); ?>">
    <input type="hidden" name="offer_product_id" value="<?php echo esc_attr($offer_product_id); ?>">

    <div class="!block">

        <div class="flex items-center h-15 justify-between px-8 albania-custom-header">
            <div>
                <img class="logo w-[150px] px-5" src="<?php echo esc_url($logo) ?>" alt="logo">
            </div>
            <div class="inline-flex h-full overflow-hidden gap-4 w-[1300px] mx-auto">
                <?php
                foreach ($menu_items as  $key => $item) :

                    $active_class = '';
                    if ($active_tab === strtolower($key)) {
                        $active_class = 'active';
                    }

                ?>
                    <span
                        class="select-none cursor-pointer inline-flex items-center gap-2 p-[15px_25px] transition-colors offer_tab_top offer_tab_onclick <?php echo esc_attr($active_class); ?>" data-tabid="<?php echo esc_attr(strtolower($key)); ?>">
                        <i class="<?php echo esc_attr($item); ?>"></i>
                        <?php echo esc_html($key); ?>
                    </span>
                <?php
                endforeach;
                ?>
            </div>

            <div>
                <?php
                $price_per_person = $helper_cls->price_calculation($post_id);
                $price_final = $helper_cls->price_calculation($post_id, 'final');
                ?>
                Total price: <b>€</b><span class="offer_price_per_person font-bold"><?php echo wp_kses_post($price_per_person); ?></span> P.P.
            </div>
        </div>

        <!-- Sections -->
        <?php
        $tabs = [
            'program',
            'flights',
            'accommodations',
            'excursions',
            'transport',
            'extras',
            'summary',
            'book'
        ];
        ?>

        <div>
            <?php
            foreach ($tabs as $tab):

                $show_display = 'none';
                if ($tab === $active_tab) {
                    $show_display = 'block';
                }
            ?>
                <div class="offer_content_display" data-showid="<?php echo esc_attr($tab); ?>" style="display: <?php echo esc_attr($show_display); ?>">
                    <?php include(TravelAlbania_PLUGIN_PATH . 'templates/travel-offer-part/' . $tab . '.php'); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</form>
<?php
get_footer();
