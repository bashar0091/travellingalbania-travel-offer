<?php
$template_id = get_post_meta($post_id, 'elementor_program_template', true);
echo do_shortcode('[elementor-template id="' . $template_id . '"]');
?>

<div>
    <span class="select-none cursor-pointer text-white inline-block p-[10px_20px] bg-[#000]" @click="setActive('flights')">Flights</span>
</div>