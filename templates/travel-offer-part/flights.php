<?php
if (has_post_thumbnail()) {
    $post_thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
} else {
    $post_thumbnail = plugin_dir_url(__FILE__) . "../assets/img/thumbnail.jpg";
}

$post_thumbnail = plugin_dir_url(dirname(__FILE__, 2)) . 'assets/img/thumbnail.jpg';

?>

<div class="!overflow-hidden">
    <div class="w-full">
        <div class="bg-[url('<?php echo esc_url($post_thumbnail_url); ?>')] bg-cover bg-center w-full">
            <div class="flex flex-col justify-end items-start px-5 w-[1300px] gap-0 text-white 2xl:h-64 lg:h-55 h-45 mx-auto">
                <p class="2xl:!text-3xl lg:!2xl !text-xl font-bold 2xl:!mb-[20px] !mb-[-5px] ">Best of Albania</p>
                <h2 class="!font-[Bebas Neue] 2xl:!text-5xl xl!text-4xl: !text-2xl !font-normal !mb-10 ">FLIGHTS</h2>
            </div>
        </div>
        <div class="!text-sm mt-10 w-full flex flex-col justify-end item-end px-5 2xl:!w-[1300px] xl:!w-[1300px]  !w-full gap-[0px] text-black mx-auto">
            <p>Do you prefer a different flight? Let us know and we'll be happy to adjust your quote accordingly.</p>
            <h2 class="!text-xl !mb-10">Please indicate your flight preference</h2>
        </div>

        <div class="px-5 overflow-x-auto w-full mx-auto">
            <div class="min-w-[1300px] max-w-[1300px] mx-auto render_flight_content">
                <?php
                render_flight_content($post_id, $helper_cls, $session_flights_id);
                ?>
            </div>
        </div>

        <div class="px-5 mt-8 w-[1300px] mb-5 mx-auto">
            <div class="select-none rounded-sm cursor-pointer text-white inline-block p-[10px_20px] bg-[#000] offer_tab_onclick" data-tabid="program">
                <i class="fa fa-arrow-left"></i>
                <span>Program</span>
            </div>
            <div class="select-none rounded-sm cursor-pointer text-white inline-block p-[10px_20px] bg-[#e73017] offer_tab_onclick" data-tabid="accommodations">
                <span>Accommodations</span>
                <i class="fa fa-arrow-right"></i>
            </div>
        </div>
    </div>
</div>