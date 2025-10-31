<?php
function render_accommodations_content($post_id, $helper_cls, $session_accommodations_id)
{
    $accommodation_date = get_post_meta($post_id, 'TravelAlbania_accommodations_repeat', true);

    if (!empty($accommodation_date) && !is_wp_error($accommodation_date)) :
        foreach ($accommodation_date as $key => $date) :
            $start_date = !empty($date['start_date']) ? gmdate('d F', $date['start_date']) : '';
            $end_date = !empty($date['end_date']) ? gmdate('d F Y', $date['end_date']) : '';
            $accommodation_select = !empty($date['accommodation_select']) ? $date['accommodation_select'] : null;
?>
            <div class="overflow-x-auto w-full mx-auto">
                <div class="2xl:!min-w-[1300px] 2xl:!max-w-[1300px] xl:!min-w-[1300px] xl:!max-w-[1300px] !min-w-[1000px] !max-w-[1000px] mx-auto px-5 mb-10">

                    <h4 class="!mb-3 !text-lg"><?php echo wp_kses_post($start_date . ' - ' . $end_date); ?></h4>

                    <?php
                    if (!empty($accommodation_select)):

                        $accommodation_hotel_terms = get_terms(array(
                            'taxonomy' => 'tta_travel_accommodations',
                            'include' => $accommodation_select,
                            'hide_empty' => false,
                        ));

                        if (!empty($accommodation_hotel_terms) && !is_wp_error($accommodation_hotel_terms)) :

                            foreach ($accommodation_hotel_terms as $hotel) :
                                $hotel_id = $hotel->term_id;
                                $title = $hotel->name;
                                $image = get_term_meta($hotel_id, 'image', true);

                                $price = 0;
                                for ($i = 1; $i <= 4; $i++) {
                                    $season_price = get_term_meta($hotel_id, "price_season_$i", true);
                                    if ($season_price) {
                                        $price = $season_price;
                                        break;
                                    }
                                }

                                $location = get_term_meta($hotel_id, 'location', true);
                                $rating = get_term_meta($hotel_id, 'rating', true);
                                $room_type = get_term_meta($hotel_id, 'room_type', true);
                                $basic = get_term_meta($hotel_id, 'basic', true);

                                for ($i = 1; $i <= 5; $i++);

                                $is_first_hotel = $i === 0 ? true : false;

                                $is_package_included = get_term_meta($hotel_id, 'is_package_included', true);
                                $is_selected =  in_array($hotel_id, array_merge(...array_values($session_accommodations_id)));
                    ?>
                                <div class="mb-5 flex items-center gap-10 rounded-lg ring ring-[#80808012] shadow-md">
                                    <div class="w-3/4 flex items-center border-r-1 border-[#80808057] pr-10">
                                        <div class="flex items-center px-5">
                                            <div class="flex-shrink-0 mr-4">
                                                <div class="w-24 h-24 bg-gray-200 rounded-md">
                                                    <img class="w-full !h-[100px] object-cover rounded-md" src="<?php echo esc_url($image); ?>" alt="">
                                                </div>
                                            </div>
                                            <div class="flex-grow w-[200px]">
                                                <p class="text-sm text-gray-600 !mb-1 !text-[var(--custom_main_color)]">
                                                    <i class="mr-1 fa fa-map-marker text-[var(--custom_main_color)]" aria-hidden="true"></i> <?php echo wp_kses_post($location); ?>
                                                </p>
                                                <h3 class="font-semibold !text-sm !mb-1"><?php echo wp_kses_post($title); ?></h3>
                                                <div class="text-yellow-500 !text-2xl !mb-0">
                                                    <?php
                                                    if (!function_exists('generate_stars')) {
                                                        function generate_stars($number)
                                                        {
                                                            $total_stars = 5;
                                                            $html = '';

                                                            // Empty or invalid → all gray stars
                                                            if (empty($number) || !is_numeric($number) || $number <= 0) {
                                                                for ($i = 1; $i <= $total_stars; $i++) {
                                                                    $html .= '<i class="fa fa-star text-gray-400 !text-sm"></i>';
                                                                }
                                                                return $html;
                                                            }

                                                            $whole = floor($number);
                                                            $fraction = $number - $whole;

                                                            for ($i = 1; $i <= $total_stars; $i++) {
                                                                if ($i <= $whole) {
                                                                    // Full red star
                                                                    $html .= '<i class="fa fa-star !text-sm" style="color:#e73017;"></i>';
                                                                } elseif ($i == $whole + 1 && $fraction > 0) {
                                                                    // Partial red star (fraction × 100)
                                                                    $percentage = round($fraction * 100, 2);
                                                                    $html .= '<i class="fa fa-star !text-sm" style="background: linear-gradient(to right, #e73017 ' . $percentage . '%, gray ' . $percentage . '%);-webkit-background-clip: text;-webkit-text-fill-color: transparent;"></i>';
                                                                } else {
                                                                    // Gray stars
                                                                    $html .= '<i class="fa fa-star !text-sm text-gray-400"></i>';
                                                                }
                                                            }

                                                            return $html;
                                                        }
                                                    }


                                                    echo generate_stars($rating);
                                                    ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex-grow ml-4 pl-4">
                                            <table class="w-full">
                                                <thead>
                                                    <tr>
                                                        <th class="text-left font-bold pb-2 pr-4 text-gray-700 !border-none !bg-transparent w-[300px]">Room Type</th>
                                                        <th class="text-left font-bold pb-2 text-gray-700 !border-none !bg-transparent">Basic</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-left pr-4 !pt-0 !border-none !bg-transparent">
                                                            <?php echo wpautop(wp_kses_post($room_type)); ?>
                                                        </td>
                                                        <td class="text-left !pt-0 !border-none !bg-transparent">
                                                            <?php echo wpautop(wp_kses_post($basic)); ?>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="flex flex-col gap-2 items-center w-1/4 justify-center ">
                                        <div class="render_flight_btn">
                                            <?php
                                            if ($is_selected):
                                                $helper_cls->delete_btn($hotel_id, 'accommodations_id', 'Choosen', 'not_delete');
                                            else:
                                                $helper_cls->select_btn($hotel_id, 'accommodations_id', $key);
                                            endif; ?>
                                        </div>

                                        <?php if (!$is_selected): ?>
                                            <span>
                                                €
                                                <?php
                                                $different_price = $helper_cls->showing_price_different($post_id, 'accommodations_id', $hotel_id, $key);

                                                if ($different_price == 0) {
                                                    echo wp_kses_post(number_format((float)$price, 2));
                                                } else {
                                                    echo $different_price;
                                                }
                                                ?> / Room
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                    <?php
                            endforeach;
                        endif;
                    endif;
                    ?>
                </div>
            </div>
<?php
        endforeach;
    else:
        echo "No Accommodations Found";
    endif;
}
