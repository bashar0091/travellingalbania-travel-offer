<?php

$helper_cls = new TravelAlbania_Init_Helper;

?>
<div class="!overflow-hidden">
    <div class="w-full">
        <div class="bg-[url('<?php echo esc_url($post_thumbnail_url); ?>')] bg-cover bg-center w-full">
            <div class="flex flex-col justify-end items-start px-5 2xl:!max-w-[1300px] 2xl!w-[1300px] xl!w-[1300px] lg:!w-full w-[1300px] gap-0 text-white 2xl:h-64 lg:h-55 h-45 mx-auto">
                <p class="2xl:!text-3xl lg:!2xl !text-xl font-bold 2xl:!mb-[20px] !mb-[-5px]">Best of Albania</p>
                <h2 class="!font-[Bebas Neue] 2xl:!text-5xl xl!text-4xl: !text-2xl !font-normal !mb-10 uppercase">Accommodations</h2>
            </div>
        </div>
        <div>
            <div class="!text-sm mt-10 w-full flex flex-col justify-end item-end px-5 2xl:!max-w-[1300px] 2xl!w-[1300px] xl!w-[1300px] lg:!w-full !w-full gap-[0px] text-black mx-auto">
                <p>Have you seen another hotel that isn't listed but still seems interesting? Let us know, and we'll be happy to adjust the quote accordingly.</p>
            </div>
        </div>

        <div class="render_accommodation_content">
            <?php
            render_accommodations_content($post_id, $helper_cls, $session_accommodations_id);
            ?>
        </div>

        <div class="mt-8 w-[1300px] px-5 mx-auto mb-8">
            <div class="select-none rounded-sm cursor-pointer text-white inline-block p-[10px_20px] bg-[#000] offer_tab_onclick" data-tabid="flights">
                <i class="fa fa-arrow-left"></i>
                <span>Flights</span>
            </div>
            <div class="select-none rounded-sm cursor-pointer text-white inline-block p-[10px_20px] bg-[#e73017] offer_tab_onclick" data-tabid="excursions">
                <span>Excursions</span>
                <i class="fa fa-arrow-right"></i>
            </div>
        </div>
    </div>
</div>