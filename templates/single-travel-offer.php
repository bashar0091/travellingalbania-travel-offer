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

$post_id = get_the_ID();
$menu_items = ['Program', 'Flights', 'Accommodations', 'Excursions', 'Transport', 'Extras', 'Summary', 'Book'];
$active_tab = isset($_GET['active']) ? sanitize_text_field($_GET['active']) : 'program';
?>
<style>
    [x-cloak] {
        display: none !important;
    }
</style>

<script src="//unpkg.com/alpinejs" defer></script>

<form class="w-full e-con py-20">
    <input type="hidden" name="offer_id" value="<?php echo esc_attr(get_the_ID()); ?>">

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

        <div class="flex items-center justify-between" style="
    position: fixed;
    width: 71%;
    margin-top: -90px;
    background: #fff;
    left: 50%;
    transform: translateX(-50%);
    padding: 15px;
">
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
        <div class="mt-8">

            <div x-show="active === 'program'" <?php if ($active_tab !== 'program') echo 'x-cloak'; ?>>
                <?php include(TravelAlbania_PLUGIN_PATH . 'templates/travel-offer-part/program.php'); ?>
            </div>

            <div x-show="active === 'flights'" <?php if ($active_tab !== 'flights') echo 'x-cloak'; ?>>
                <?php include(TravelAlbania_PLUGIN_PATH . 'templates/travel-offer-part/flights.php'); ?>
            </div>

            <div x-show="active === 'accommodations'" <?php if ($active_tab !== 'accommodations') echo 'x-cloak'; ?>>
                <?php include(TravelAlbania_PLUGIN_PATH . 'templates/travel-offer-part/accommodations.php'); ?>
            </div>

            <div x-show="active === 'excursions'" <?php if ($active_tab !== 'excursions') echo 'x-cloak'; ?>>
                <?php include(TravelAlbania_PLUGIN_PATH . 'templates/travel-offer-part/excursions.php'); ?>
            </div>

            <div x-show="active === 'transport'" <?php if ($active_tab !== 'transport') echo 'x-cloak'; ?>>
                <h1>Transport</h1>
                <p>Transport content...</p>
                <div>
                    <span class="select-none cursor-pointer text-white inline-block p-[10px_20px] bg-[#000]" @click="setActive('excursions')">Excursions</span>
                    <span class="select-none cursor-pointer text-white inline-block p-[10px_20px] bg-[#bf3d2a]" @click="setActive('extras')">Extras</span>
                </div>
            </div>

            <div x-show="active === 'extras'" <?php if ($active_tab !== 'extras') echo 'x-cloak'; ?>>
                <h1>Extras</h1>
                <p>Extras content...</p>
                <div>
                    <span class="select-none cursor-pointer text-white inline-block p-[10px_20px] bg-[#000]" @click="setActive('transport')">Transport</span>
                    <span class="select-none cursor-pointer text-white inline-block p-[10px_20px] bg-[#bf3d2a]" @click="setActive('summary')">Summary</span>
                </div>
            </div>

            <div x-show="active === 'summary'" <?php if ($active_tab !== 'summary') echo 'x-cloak'; ?>>
                <h1>Summary</h1>
                <p>Summary content...</p>
                <div>
                    <span class="select-none cursor-pointer text-white inline-block p-[10px_20px] bg-[#000]" @click="setActive('extras')">Extras</span>
                    <span class="select-none cursor-pointer text-white inline-block p-[10px_20px] bg-[#bf3d2a]" @click="setActive('book')">Book</span>
                </div>
            </div>

            <div x-show="active === 'book'" <?php if ($active_tab !== 'book') echo 'x-cloak'; ?>>
                <h1>Book</h1>
                <p>Book content...</p>
                <div>
                    <span class="select-none cursor-pointer text-white inline-block p-[10px_20px] bg-[#000]" @click="setActive('summary')">Summary</span>
                </div>
            </div>
        </div>
    </div>
</form>
<?php
get_footer();
