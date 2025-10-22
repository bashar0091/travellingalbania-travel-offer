<?php

$helper_cls = new TravelAlbania_Init_Helper;

?>
<div>
    <div>
        <div class="bg-[url('<?php echo esc_url($post_thumbnail_url); ?>')] bg-cover bg-center w-full">
            <div class="flex flex-col justify-end items-start px-5 w-[1300px] gap-0 text-white h-64 mx-auto">
                <p class="!text-3xl font-bold">Best of Albania</p>
                <h2 class="!font-[Bebas Neue] !text-5xl !font-normal !mb-10 uppercase">Accommodations</h2>
            </div>
        </div>
        <div>
            <div class="!text-sm mt-10 w-full flex flex-col justify-end item-end px-5 !w-[1300px] gap-[0px] text-black mx-auto">
                <p>Have you seen another hotel that isn't listed but still seems interesting? Let us know, and we'll be happy to adjust the quote accordingly.</p>
            </div>
        </div>
        <?php

        $accommodation_date_terms = wp_get_post_terms($post_id, 'tta_travel_accommodations', array(
            'parent' => 0,
            'hide_empty' => false,
        ));

        if (!empty($accommodation_date_terms) && !is_wp_error($accommodation_date_terms)) :
            foreach ($accommodation_date_terms as $date) :
        ?>
                <div class="max-w-[1300px] mx-auto">

                    <h4 class="!mb-5 px-5 !text-lg"><?php echo wp_kses_post($date->name); ?></h4>

                    <?php

                    $accommodation_hotel_terms = get_terms(array(
                        'taxonomy' => 'tta_travel_accommodations',
                        'parent' => $date->term_id,
                        'hide_empty' => false,
                    ));

                    if (!empty($accommodation_hotel_terms) && !is_wp_error($accommodation_hotel_terms)) :

                        $i = 0;

                        foreach ($accommodation_hotel_terms as $hotel) :
                            $hotel_id = $hotel->term_id;
                            $title = $hotel->name;
                            $image = get_term_meta($hotel_id, 'image', true);
                            $price = get_term_meta($hotel_id, 'price', true);
                            $location = get_term_meta($hotel_id, 'location', true);
                            $rating = get_term_meta($hotel_id, 'rating', true);
                            $room_type = get_term_meta($hotel_id, 'room_type', true);
                            $basic = get_term_meta($hotel_id, 'basic', true);

                            for ($i = 1; $i <= 5; $i++);

                            $is_first_hotel = $i === 0 ? true : false;

                            $is_package_included = get_term_meta($hotel_id, 'is_package_included', true);
                            $is_selected = in_array($hotel_id, $session_accommodations_id);
                    ?>
                            <?php if (!$is_first_hotel) : ?>
                                <div class="max-w-[1300px] mx-auto px-5">
                                <?php endif; ?>

                                <div class="mt-5 flex items-center gap-10 rounded-lg ring ring-[#80808012] shadow-md">
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
                                                                    $html .= '<i class="fa fa-star !text-sm" style="
                    background: linear-gradient(to right, #e73017 ' . $percentage . '%, gray ' . $percentage . '%);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                "></i>';
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
                                                        <th class="text-left font-bold pb-2 pr-4 text-gray-700 !border-none !bg-transparent">Room Type</th>
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
                                            if ($is_package_included === 'yes'):
                                                echo '<span class="text-green-800">Package Included</span>';
                                            elseif ($is_selected):
                                                $helper_cls->delete_btn($hotel_id, 'accommodations_id');
                                            else:
                                                $helper_cls->select_btn($hotel_id, 'accommodations_id');
                                            endif; ?>
                                        </div>
                                        <?php
                                        if ($is_package_included !== 'yes' && $price) :
                                            echo '<span>+ €' . number_format((float)$price, 2) . '</span>';
                                        endif;
                                        ?>
                                    </div>

                                </div>
                                </div>

                                <?php if (!$is_first_hotel) : ?>
                </div>
            <?php endif; ?>
<?php
                        endforeach;
                    endif;

                    echo '<br>';
                endforeach;
            else:
                echo "No Accommodations Found";
            endif;

?>



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