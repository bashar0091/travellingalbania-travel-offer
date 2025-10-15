<?php

get_header();

$menu_items = ['Program', 'Flights', 'Accommodations', 'Excursions', 'Transport', 'Extras', 'Summary', 'Book'];
$active_tab = isset($_GET['active']) ? sanitize_text_field($_GET['active']) : 'Program';
?>
<style>
    [x-cloak] {
        display: none !important;
    }
</style>

<form class="w-full e-con py-20">
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

        <!-- Top menu -->
        <div class="inline-flex bg-[#000] rounded-lg overflow-hidden">
            <?php foreach ($menu_items as $item) :
                $active_class = ($active_tab === $item) ? 'bg-[#bf3d2a]' : '';
            ?>
                <span
                    class="select-none cursor-pointer hover:bg-[#bf3d2a] inline-block p-[15px_25px] text-white transition-colors <?php echo $active_class; ?>"
                    :class="{ 'bg-[#bf3d2a]': active === '<?php echo esc_js($item); ?>' }"
                    @click="setActive('<?php echo esc_js($item); ?>')">
                    <?php echo esc_html($item); ?>
                </span>
            <?php endforeach; ?>
        </div>

        <!-- Sections -->
        <div class="mt-8">

            <div x-show="active === 'Program'" <?php if ($active_tab !== 'Program') echo 'x-cloak'; ?>>
                <h1>Program</h1>
                <p>Program content...</p>
                <div>
                    <span class="select-none cursor-pointer text-white inline-block p-[10px_20px] bg-[#000]" @click="setActive('Flights')">Flights</span>
                </div>
            </div>

            <div x-show="active === 'Flights'" <?php if ($active_tab !== 'Flights') echo 'x-cloak'; ?>>
                <h1>Flights</h1>
                <p>Flights content...</p>
                <div>
                    <span class="select-none cursor-pointer text-white inline-block p-[10px_20px] bg-[#000]" @click="setActive('Program')">Program</span>
                    <span class="select-none cursor-pointer text-white inline-block p-[10px_20px] bg-[#bf3d2a]" @click="setActive('Accommodations')">Accommodations</span>
                </div>
            </div>

            <div x-show="active === 'Accommodations'" <?php if ($active_tab !== 'Accommodations') echo 'x-cloak'; ?>>
                <h1>Accommodations</h1>
                <p>Accommodations content...</p>
                <div>
                    <span class="select-none cursor-pointer text-white inline-block p-[10px_20px] bg-[#000]" @click="setActive('Flights')">Flights</span>
                    <span class="select-none cursor-pointer text-white inline-block p-[10px_20px] bg-[#bf3d2a]" @click="setActive('Excursions')">Excursions</span>
                </div>
            </div>

            <div x-show="active === 'Excursions'" <?php if ($active_tab !== 'Excursions') echo 'x-cloak'; ?>>
                <h1>Excursions</h1>
                <p>Excursions content...</p>
                <div>
                    <span class="select-none cursor-pointer text-white inline-block p-[10px_20px] bg-[#000]" @click="setActive('Accommodations')">Accommodations</span>
                    <span class="select-none cursor-pointer text-white inline-block p-[10px_20px] bg-[#bf3d2a]" @click="setActive('Transport')">Transport</span>
                </div>
            </div>

            <div x-show="active === 'Transport'" <?php if ($active_tab !== 'Transport') echo 'x-cloak'; ?>>
                <h1>Transport</h1>
                <p>Transport content...</p>
                <div>
                    <span class="select-none cursor-pointer text-white inline-block p-[10px_20px] bg-[#000]" @click="setActive('Excursions')">Excursions</span>
                    <span class="select-none cursor-pointer text-white inline-block p-[10px_20px] bg-[#bf3d2a]" @click="setActive('Extras')">Extras</span>
                </div>
            </div>

            <div x-show="active === 'Extras'" <?php if ($active_tab !== 'Extras') echo 'x-cloak'; ?>>
                <h1>Extras</h1>
                <p>Extras content...</p>
                <div>
                    <span class="select-none cursor-pointer text-white inline-block p-[10px_20px] bg-[#000]" @click="setActive('Transport')">Transport</span>
                    <span class="select-none cursor-pointer text-white inline-block p-[10px_20px] bg-[#bf3d2a]" @click="setActive('Summary')">Summary</span>
                </div>
            </div>

            <div x-show="active === 'Summary'" <?php if ($active_tab !== 'Summary') echo 'x-cloak'; ?>>
                <h1>Summary</h1>
                <p>Summary content...</p>
                <div>
                    <span class="select-none cursor-pointer text-white inline-block p-[10px_20px] bg-[#000]" @click="setActive('Extras')">Extras</span>
                    <span class="select-none cursor-pointer text-white inline-block p-[10px_20px] bg-[#bf3d2a]" @click="setActive('Book')">Book</span>
                </div>
            </div>

            <div x-show="active === 'Book'" <?php if ($active_tab !== 'Book') echo 'x-cloak'; ?>>
                <h1>Book</h1>
                <p>Book content...</p>
                <div>
                    <span class="select-none cursor-pointer text-white inline-block p-[10px_20px] bg-[#000]" @click="setActive('Summary')">Summary</span>
                </div>
            </div>
        </div>
    </div>
</form>
<?php
get_footer();
