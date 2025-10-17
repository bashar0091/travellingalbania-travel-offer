<?php

$helper_cls = new TravelAlbania_Init_Helper;

$excursion_date_terms = wp_get_post_terms($post_id, 'tta_travel_excursions', array(
    'parent' => 0,
    'hide_empty' => false,
));

if (!empty($excursion_date_terms) && !is_wp_error($excursion_date_terms)) :
    foreach ($excursion_date_terms as $date) :
?>

        <h4 class="!mb-5"><?php echo wp_kses_post($date->name); ?></h4>

        <?php

        $excursion_hotel_terms = get_terms(array(
            'taxonomy' => 'tta_travel_excursions',
            'parent' => $date->term_id,
            'hide_empty' => false,
        ));

        if (!empty($excursion_hotel_terms) && !is_wp_error($excursion_hotel_terms)) :
            foreach ($excursion_hotel_terms as $hotel) :
                $hotel_id = $hotel->term_id;
                $title = $hotel->name;
                $image = get_term_meta($hotel_id, 'image', true);
                $location = get_term_meta($hotel_id, 'location', true);
                $content = get_term_meta($hotel_id, 'content', true);

                $is_package_included = get_term_meta($hotel_id, 'is_package_included', true);
                $is_selected = in_array($hotel_id, $session_excursions_id);
        ?>
                <div class="flex items-center gap-10 p-4 border rounded-lg shadow-md bg-white mb-5">
                    <div class="w-3/4 flex items-center">
                        <div class="w-1/4 flex-shrink-0 mr-4">
                            <div class="bg-gray-200 rounded-md">
                                <img class="w-full !h-full" src="<?php echo esc_url($image); ?>" alt="">
                            </div>
                        </div>
                        <div class="w-3/4 flex-grow flex-1">
                            <h3 class="font-semibold text-lg !mb-1"><?php echo wp_kses_post($title); ?></h3>
                            <p class="text-sm text-gray-600 !mb-1">
                                <span class="mr-1">📍</span> <?php echo wp_kses_post($location); ?>
                            </p>
                            <div>
                                <?php echo wpautop(wp_kses_post($content)); ?>
                            </div>
                        </div>
                    </div>

                    <div class="w-1/4 render_flight_btn">
                        <?php
                        if ($is_package_included === 'yes'):
                            echo '<b class="text-green-800">Package Included</b>';
                        elseif ($is_selected):
                            $helper_cls->delete_btn($hotel_id, 'excursions_id');
                        else:
                            $helper_cls->select_btn($hotel_id, 'excursions_id');
                        endif; ?>
                    </div>
                </div>
<?php
            endforeach;
        endif;

        echo '<br>';
    endforeach;
else:
    echo "No Excursions Found";
endif;

?>

<div>
    <span class="select-none cursor-pointer text-white inline-block p-[10px_20px] bg-[#000]" @click="setActive('accommodations')">Accommodations</span>
    <span class="select-none cursor-pointer text-white inline-block p-[10px_20px] bg-[#bf3d2a]" @click="setActive('transport')">Transport</span>
</div>