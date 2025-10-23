<?php

$helper_cls = new TravelAlbania_Init_Helper;

?>
<div>
    <div>
        <div class="bg-[url('<?php echo esc_url($post_thumbnail_url); ?>')] bg-cover bg-center w-full">
            <div class="flex flex-col justify-end items-start px-5 w-[1300px] gap-0 text-white h-64 mx-auto">
                <p class="!text-3xl font-bold">Best of Albania</p>
                <h2 class="!font-[Bebas Neue] !text-5xl !font-normal !mb-10 uppercase">Transport</h2>
            </div>
        </div>
        <div>
            <div class="!text-sm mt-10 w-full flex flex-col justify-end item-end px-5 !w-[1300px] gap-[0px] text-black mx-auto">
                <p>Prefer to rent a different vehicle? Let us know, and we'll be happy to adjust your quote accordingly.</p>
            </div>
        </div>
        <?php
        $transport_date = get_post_meta($post_id, 'TravelAlbania_transports_repeat', true);

        if (!empty($transport_date) && !is_wp_error($transport_date)) :
            foreach ($transport_date as $date) :
                $start_date = get_gmt_from_date($date['start_date'], 'd F');
                $end_date = get_gmt_from_date($date['end_date'], 'd F Y');
                $transport_select = !empty($date['transport_select']) ? $date['transport_select'] : null;
        ?>

                <div class="max-w-[1300px] mx-auto px-5">
                    <h4 class="!mb-3 !text-lg"><?php echo wp_kses_post($start_date . ' - ' . $end_date); ?></h4>

                    <?php
                    if (!empty($transport_select)):
                        $transport_hotel_terms = get_terms(array(
                            'taxonomy' => 'tta_travel_transports',
                            'include' => $transport_select,
                            'hide_empty' => false,
                        ));

                        if (!empty($transport_hotel_terms) && !is_wp_error($transport_hotel_terms)) :

                            foreach ($transport_hotel_terms as $hotel) :
                                $hotel_id = $hotel->term_id;
                                $title = $hotel->name;
                                $image = get_term_meta($hotel_id, 'image', true);
                                $price = get_term_meta($hotel_id, 'price', true);
                                $location = get_term_meta($hotel_id, 'location', true);
                                $included = get_term_meta($hotel_id, 'included', true);
                                $excluded = get_term_meta($hotel_id, 'excluded', true);
                                $is_first_hotel = ($i === 0);

                                $is_package_included = get_term_meta($hotel_id, 'is_package_included', true);
                                $is_selected = in_array($hotel_id, $session_transports_id);
                    ?>

                                <div class="mt-5 flex items-center gap-10 rounded-lg ring ring-[#80808012] shadow-md">
                                    <div class="w-3/4 flex items-center border-r-1 border-[#80808057] p-5">
                                        <div class="w-1/4 flex-shrink-0 mr-4">
                                            <div class="rounded-md">
                                                <img class="w-full !h-[130px] object-cover rounded-md" src="<?php echo esc_url($image); ?>" alt="">
                                            </div>
                                        </div>
                                        <div class="w-3/4 flex-grow flex-1">
                                            <p class="text-sm text-gray-600 !mb-1">
                                                <i class="mr-1 fa fa-map-marker text-[var(--custom_main_color)]" aria-hidden="true"></i> <?php echo wp_kses_post($location); ?>
                                            </p>
                                            <h3 class="font-semibold !text-lg !mb-1"><?php echo wp_kses_post($title); ?></h3>
                                            <div class="flex !gap-12">
                                                <div class="flex-1">
                                                    <h6 class="text-sm">Included</h6>
                                                    <?php
                                                    if (!empty($included)) {
                                                        // Remove existing list tags or stray dashes
                                                        $cleaned = strip_tags($included);
                                                        $cleaned = str_replace('-', '', $cleaned);

                                                        // Split into lines
                                                        $lines = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $cleaned)));

                                                        if (!empty($lines)) {
                                                            echo '<ul class="list-none !space-y-1">';
                                                            foreach ($lines as $line) {
                                                                echo '<li class="flex items-start !gap-1 !text-sm text-gray-500">';
                                                                echo '<i class="fa fa-check-circle mt-1 !text-green-800" aria-hidden="true"></i>';
                                                                echo '<span>' . esc_html($line) . '</span>';
                                                                echo '</li>';
                                                            }
                                                            echo '</ul>';
                                                        }
                                                    }
                                                    ?>
                                                </div>

                                                <div class="flex-1">
                                                    <h6>Excluded</h6>
                                                    <?php
                                                    if (!empty($excluded)) {
                                                        // Remove existing list tags or stray dashes
                                                        $cleaned = strip_tags($excluded);
                                                        $cleaned = str_replace('-', '', $cleaned);

                                                        // Split into lines
                                                        $lines = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $cleaned)));

                                                        if (!empty($lines)) {
                                                            echo '<ul class="list-none !space-y-2">';
                                                            foreach ($lines as $line) {
                                                                echo '<li class="flex items-start !gap-1 !text-sm text-gray-500">';
                                                                echo '<i class="fa fa-times-circle mt-1 !text-[var(--custom_main_color)]" aria-hidden="true"></i>';
                                                                echo '<span>' . esc_html($line) . '</span>';
                                                                echo '</li>';
                                                            }
                                                            echo '</ul>';
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center flex-col gap-2  w-1/4 justify-center ">
                                        <div class="render_flight_btn">
                                            <?php
                                            if ($is_package_included === 'yes') :
                                                echo '<span class="text-green-800">Package Included</span>';
                                            elseif ($is_selected) :
                                                $helper_cls->delete_btn($hotel_id, 'transports_id');
                                            else :
                                                $helper_cls->select_btn($hotel_id, 'transports_id');
                                            endif;
                                            ?>
                                        </div>

                                        <span>
                                            <?php echo $is_package_included !== 'yes' && $price ? "+" : ""; ?>
                                            €
                                            <?php echo wp_kses_post(number_format((float)$price, 2)); ?> / Day
                                        </span>
                                    </div>

                                </div>

                    <?php
                            endforeach;
                        endif;
                    endif;
                    ?>

                </div>
        <?php
            endforeach;
        else:
            echo "No Transports Found";
        endif;

        ?>

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