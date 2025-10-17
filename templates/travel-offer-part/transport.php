<?php

$helper_cls = new TravelAlbania_Init_Helper;

$transport_date_terms = wp_get_post_terms($post_id, 'tta_travel_transports', array(
    'parent' => 0,
    'hide_empty' => false,
));

if (!empty($transport_date_terms) && !is_wp_error($transport_date_terms)) :
    foreach ($transport_date_terms as $date) :
?>

        <h4 class="!mb-5"><?php echo wp_kses_post($date->name); ?></h4>

        <?php

        $transport_hotel_terms = get_terms(array(
            'taxonomy' => 'tta_travel_transports',
            'parent' => $date->term_id,
            'hide_empty' => false,
        ));

        if (!empty($transport_hotel_terms) && !is_wp_error($transport_hotel_terms)) :
            foreach ($transport_hotel_terms as $hotel) :
                $hotel_id = $hotel->term_id;
                $title = $hotel->name;
                $image = get_term_meta($hotel_id, 'image', true);
                $location = get_term_meta($hotel_id, 'location', true);
                $included = get_term_meta($hotel_id, 'included', true);
                $excluded = get_term_meta($hotel_id, 'excluded', true);

                $is_package_included = get_term_meta($hotel_id, 'is_package_included', true);
                $is_selected = in_array($hotel_id, $session_transports_id);
        ?>
                <div class="flex items-center gap-10 p-4 border rounded-lg shadow-md bg-white mb-5">
                    <div class="w-3/4 flex">
                        <div class="w-1/4 flex-shrink-0 mr-4">
                            <div class="bg-gray-200 rounded-md">
                                <img class="w-full !h-full" src="<?php echo esc_url($image); ?>" alt="">
                            </div>
                        </div>
                        <div class="w-3/4 flex-grow flex-1">
                            <p class="text-sm text-gray-600 !mb-1">
                                <span class="mr-1">📍</span> <?php echo wp_kses_post($location); ?>
                            </p>
                            <h3 class="font-semibold text-lg !mb-1"><?php echo wp_kses_post($title); ?></h3>
                            <div class="flex">
                                <div class="flex-1">
                                    <h6>Included</h6>
                                    <?php echo wpautop(wp_kses_post($included)); ?>
                                </div>

                                <div class="flex-1">
                                    <h6>Excluded</h6>
                                    <?php echo wpautop(wp_kses_post($excluded)); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-1/4 render_flight_btn">
                        <?php
                        if ($is_package_included === 'yes'):
                            echo '<b class="text-green-800">Package Included</b>';
                        elseif ($is_selected):
                            $helper_cls->delete_btn($hotel_id, 'transports_id');
                        else:
                            $helper_cls->select_btn($hotel_id, 'transports_id');
                        endif; ?>
                    </div>
                </div>
<?php
            endforeach;
        endif;

        echo '<br>';
    endforeach;
else:
    echo "No Transports Found";
endif;

?>

<div>
    <span class="select-none cursor-pointer text-white inline-block p-[10px_20px] bg-[#000]" @click="setActive('excursions')">Excursions</span>
    <span class="select-none cursor-pointer text-white inline-block p-[10px_20px] bg-[#bf3d2a]" @click="setActive('summary')">Summary</span>
</div>