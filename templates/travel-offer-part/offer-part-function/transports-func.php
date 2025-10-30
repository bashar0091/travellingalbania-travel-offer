<?php
function render_transport_content($post_id, $helper_cls, $session_transports_id)
{
    $transport_date = get_post_meta($post_id, 'TravelAlbania_transports_repeat', true);

    if (!empty($transport_date) && !is_wp_error($transport_date)) :
        foreach ($transport_date as $date) :
            $start_date = get_gmt_from_date($date['start_date'], 'd F');
            $end_date = get_gmt_from_date($date['end_date'], 'd F Y');
            $transport_select = !empty($date['transport_select']) ? $date['transport_select'] : null;
?>

            <div class="overflow-x-auto w-full mx-auto">
                <div class="mb-10 2xl:max-w-[1300px] 2xl:min-w-[1300px] xl:max-w-[1300px] xl:min-w-[1300px] md:max-w-[1000px] md:min-w-[1000px]  max-w-[800px] min-w-[800px] mx-auto px-5">
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

                                $is_package_included = get_term_meta($hotel_id, 'is_package_included', true);
                                $is_selected = in_array($hotel_id, $session_transports_id);
                    ?>

                                <div class="mt-5 flex items-center gap-10 rounded-lg ring ring-[#80808012] shadow-md">
                                    <div class="w-3/4 flex items-center border-r-1 border-[#80808057] p-5">
                                        <div class="w-1/4 flex-shrink-0 mr-4">
                                            <div class="rounded-md">
                                                <img class="w-full !h-[130px] object-contain rounded-md" src="<?php echo esc_url($image); ?>" alt="">
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
                                            if ($is_selected):
                                                $helper_cls->delete_btn($hotel_id, 'transports_id', 'Choosen', 'not_delete');
                                            else:
                                                $helper_cls->select_btn($hotel_id, 'transports_id');
                                            endif; ?>
                                        </div>

                                        <span>
                                            €
                                            <?php
                                            $different_price = $helper_cls->showing_price_different($post_id, 'transports_id', $hotel_id);

                                            if ($different_price == 0) {
                                                echo wp_kses_post(number_format((float)$price, 2));
                                            } else {
                                                echo $different_price;
                                            }
                                            ?> / Day
                                        </span>
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
        echo "No Transports Found";
    endif;
}
