<?php
get_header();

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

$post_id = get_the_ID();

$menu_items = ['Program', 'Flights', 'Accommodations', 'Excursions', 'Transport', 'Summary', 'Book'];

$active_tab = isset($_GET['active']) ? sanitize_text_field($_GET['active']) : 'program';

$offer_product_id = get_post_meta($post_id, 'connect_woocommerce', true);
?>
<style>
    [x-cloak] {
        display: none !important;
    }
</style>

<script src="//unpkg.com/alpinejs" defer></script>

<form class="w-full e-con py-20" method="POST">
    <input type="hidden" name="offer_id" value="<?php echo esc_attr($post_id); ?>">
    <input type="hidden" name="offer_product_id" value="<?php echo esc_attr($offer_product_id); ?>">

    <div class="!block e-con-inner"
        x-data="{
            active: '<?php echo esc_js($active_tab); ?>',
            init() {
                const params = new URLSearchParams(window.location.search);
                const q = params.get('active');
                if (q) this.active = q;
            },
            setActive(tab) {
                this.active = tab;
                const url = new URL(window.location);
                url.searchParams.set('active', tab);
                window.history.pushState({}, '', url);
            }
         }"
        x-init="init()">

        <div class="flex items-center justify-between">
            <div class="inline-flex bg-[#000] rounded-lg overflow-hidden">
                <?php foreach ($menu_items as $item) :
                    $active_class = ($active_tab === strtolower($item)) ? 'bg-[#bf3d2a]' : '';
                ?>
                    <span
                        class="select-none cursor-pointer hover:bg-[#bf3d2a] inline-block p-[15px_25px] text-white transition-colors <?php echo $active_class; ?>"
                        :class="{ 'bg-[#bf3d2a]': active === '<?php echo esc_js(strtolower($item)); ?>' }"
                        @click="setActive('<?php echo esc_js(strtolower($item)); ?>')">
                        <?php echo esc_html($item); ?>
                    </span>
                <?php endforeach; ?>
            </div>

            <div>
                <?php
                $helper_cls = new TravelAlbania_Init_Helper();
                $total_price = $helper_cls->price_calculation();
                ?>
                Total price: € <span class="offer_total_price"><?php echo wp_kses_post($total_price); ?></span> р.р.
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
            'summary',
            'book'
        ];
        ?>

        <div class="mt-8">
            <?php foreach ($tabs as $tab): ?>
                <div x-show="active === '<?= $tab ?>'" <?php if ($active_tab !== $tab) echo 'x-cloak'; ?>>
                    <?php include(TravelAlbania_PLUGIN_PATH . 'templates/travel-offer-part/' . $tab . '.php'); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</form>
<?php
get_footer();
