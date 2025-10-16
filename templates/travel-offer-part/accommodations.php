<?php

$helper_cls = new TravelAlbania_Init_Helper;

$accommodation_date_terms = wp_get_post_terms($post_id, 'tta_travel_accommodations', array(
    'parent' => 0,
    'hide_empty' => false,
));

if (!empty($accommodation_date_terms) && !is_wp_error($accommodation_date_terms)) :
    foreach ($accommodation_date_terms as $date) :
?>

        <h4 class="!mb-5"><?php echo wp_kses_post($date->name); ?></h4>

        <?php

        $accommodation_hotel_terms = get_terms(array(
            'taxonomy' => 'tta_travel_accommodations',
            'parent' => $date->term_id,
            'hide_empty' => false,
        ));

        if (!empty($accommodation_hotel_terms) && !is_wp_error($accommodation_hotel_terms)) :
            foreach ($accommodation_hotel_terms as $hotel) :
                $hotel_id = $hotel->term_id;
                $title = $hotel->name;
                $image = get_term_meta($hotel_id, 'image', true);
                $location = get_term_meta($hotel_id, 'location', true);
                $rating = get_term_meta($hotel_id, 'rating', true);
                $room_type = get_term_meta($hotel_id, 'room_type', true);
                $basic = get_term_meta($hotel_id, 'basic', true);

                $is_selected = in_array($hotel_id, $session_accommodations_id);
        ?>
                <div class="flex items-center gap-10 p-4 border rounded-lg shadow-md bg-white mb-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 mr-4">
                            <div class="w-24 h-24 bg-gray-200 rounded-md">
                                <img class="w-full !h-full" src="<?php echo esc_url($image); ?>" alt="">
                            </div>
                        </div>
                        <div class="flex-grow">
                            <p class="text-sm text-gray-600 !mb-1">
                                <span class="mr-1">📍</span> <?php echo wp_kses_post($location); ?>
                            </p>
                            <h3 class="font-semibold text-lg !mb-1"><?php echo wp_kses_post($title); ?></h3>
                            <p class="text-yellow-500 text-xl !mb-0">
                                <?php
                                for ($i = 0; $i < $rating; $i++) {
                                    echo '⭐';
                                }
                                ?>
                            </p>
                        </div>
                    </div>

                    <div class="flex-grow ml-4 pl-4">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="text-left font-bold pb-2 pr-4 text-gray-700">Room Type</th>
                                    <th class="text-left font-bold pb-2 text-gray-700">Basic</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-left pr-4">
                                        <?php echo wpautop(wp_kses_post($room_type)); ?>
                                    </td>
                                    <td class="text-left">
                                        <?php echo wpautop(wp_kses_post($basic)); ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="w-1/4 render_flight_btn">
                        <?php
                        if ($is_selected):
                            $helper_cls->delete_btn($hotel_id, 'accommodations_id');
                        else:
                            $helper_cls->select_btn($hotel_id, 'accommodations_id');
                        endif; ?>
                    </div>
                </div>
<?php
            endforeach;
        endif;

        echo '<br>';
    endforeach;
else:
    echo "No Accommodations Found";
endif;

?>

<div>
    <span class="select-none cursor-pointer text-white inline-block p-[10px_20px] bg-[#000]" @click="setActive('flights')">Flights</span>
    <span class="select-none cursor-pointer text-white inline-block p-[10px_20px] bg-[#bf3d2a]" @click="setActive('excursions')">Excursions</span>
</div>