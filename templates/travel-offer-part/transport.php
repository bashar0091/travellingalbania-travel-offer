<?php

$helper_cls = new TravelAlbania_Init_Helper;

?>
<div class="!overflow-hidden">
    <div>
        <div class="bg-[url('<?php echo esc_url($post_thumbnail_url); ?>')] bg-cover bg-center w-full">
            <div class="flex flex-col justify-end items-start px-5 w-[1300px] gap-0 text-white 2xl:h-64 lg:h-55 h-45 mx-auto">
                <p class="2xl:!text-3xl lg:!2xl !text-xl font-bold 2xl:!mb-[20px] !mb-[-5px]">Best of Albania</p>
                <h2 class="!font-[Bebas Neue] 2xl:!text-5xl xl!text-4xl: !text-2xl !font-normal !mb-10 uppercase">Transport</h2>
            </div>
        </div>
        <div>
            <div class="!text-sm mt-10 w-full flex flex-col justify-end item-end px-5 !w-[1300px] gap-[0px] text-black mx-auto">
                <p>Prefer to rent a different vehicle? Let us know, and we'll be happy to adjust your quote accordingly.</p>
            </div>
        </div>

        <div class="render_transport_content">
            <?php
            render_transport_content($post_id, $helper_cls, $session_transports_id);
            ?>
        </div>

        <div class="mt-8 w-[1300px] mx-auto mb-8 px-5">
            <div class="select-none cursor-pointer rounded-sm text-white inline-block p-[10px_20px] bg-[#000] offer_tab_onclick" data-tabid="excursions">
                <i class="fa fa-arrow-left"></i>
                <span>Excursions</span>
            </div>
            <div class="select-none cursor-pointer rounded-sm text-white inline-block p-[10px_20px] bg-[#e73017] offer_tab_onclick" data-tabid="summary">
                <span>Summary</span>
                <i class="fa fa-arrow-right"></i>
            </div>
        </div>
    </div>
</div>