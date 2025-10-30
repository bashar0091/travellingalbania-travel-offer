<?php
$template_id = get_post_meta($post_id, 'elementor_program_template', true);
echo do_shortcode('[elementor-template id="' . $template_id . '"]');
?>

<div class="overflow-hidden">
    <div class="px-5 mt-8 w-[1300px] mx-auto">
        <div class="select-none  rounded-sm  cursor-pointer text-white inline-block p-[10px_20px] bg-[#000] offer_tab_onclick" data-tabid="flights">
            <span>Flights</span>
            <i class="fa fa-arrow-right"></i>
        </div>

    </div>
</div>