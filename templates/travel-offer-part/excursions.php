<?php

$helper_cls = new TravelAlbania_Init_Helper;
?>
<div>
    <div class="bg-[url('<?php echo esc_url($post_thumbnail_url); ?>')] bg-cover bg-center w-full">
        <div class="flex flex-col justify-end items-start px-5 w-[1300px] gap-0 text-white h-64 mx-auto">
            <p class="!text-3xl font-bold">Best of Albania</p>
            <h2 class="!font-[Bebas Neue] !text-5xl !font-normal !mb-10 uppercase">Excursions</h2>
        </div>
    </div>
    <div>
        <div class="!text-sm mt-10 w-full flex flex-col justify-end item-end px-5 !w-[1300px] gap-[0px] text-black mx-auto">
            <p>Looking for other excursions? Let us know, and we'll be happy to add them to your quote.</p>
        </div>
    </div>
    <?php

    $excursion_date_terms = wp_get_post_terms($post_id, 'tta_travel_excursions', array(
        'parent' => 0,
        'hide_empty' => false,
    ));

    if (!empty($excursion_date_terms) && !is_wp_error($excursion_date_terms)) :
        foreach ($excursion_date_terms as $date) :
    ?>

            <div class="max-w-[1300px] mx-auto px-5">

                <h4 class="!mb-5"><?php echo wp_kses_post($date->name); ?></h4>

                <?php

                $excursion_hotel_terms = get_terms(array(
                    'taxonomy' => 'tta_travel_excursions',
                    'parent' => $date->term_id,
                    'hide_empty' => false,
                ));





                if (!empty($excursion_hotel_terms) && !is_wp_error($excursion_hotel_terms)) :

                    $i = 0;

                    foreach ($excursion_hotel_terms as $hotel) :
                        $hotel_id = $hotel->term_id;
                        $title = $hotel->name;
                        $image = get_term_meta($hotel_id, 'image', true);
                        $price = get_term_meta($hotel_id, 'price', true);
                        $location = get_term_meta($hotel_id, 'location', true);
                        $content = get_term_meta($hotel_id, 'content', true);
                        $is_first_hotel = ($i === 0);
                        $is_package_included = get_term_meta($hotel_id, 'is_package_included', true);
                        $is_selected = in_array($hotel_id, $session_excursions_id);

                        $i = 1;
                        $i <= 5;
                        $i++
                ?>
                        <?php if (!$is_first_hotel) : ?>
                            <div class="max-w-[1300px] mx-auto px-5">
                            <?php endif; ?>

                            <div class="mt-5 flex items-center gap-10 rounded-lg ring ring-[#80808012] shadow-md">
                                <div class="flex items-center justify-between">
                                    <div class="w-3/4 flex items-center border-r-1 border-[#80808057] pr-10 pl-5">
                                        <div class="w-1/4 flex-shrink-0 mr-4 pt-5 pb-5">
                                            <div class="bg-gray-200 rounded-md">
                                                <img class="w-full !h-[130px] object-cover rounded-md" src="<?php echo esc_url($image); ?>" alt="">
                                            </div>
                                        </div>
                                        <div class="w-3/4 flex-grow flex-1">
                                            <h3 class="font-semibold !text-sm !mb-1"><?php echo wp_kses_post($title); ?></h3>
                                            <p class="text-sm text-gray-600 !mb-1">
                                                <i class="mr-1 fa fa-map-marker text-[var(--custom_main_color)]" aria-hidden="true"></i> <?php echo wp_kses_post($location); ?>
                                            </p>
                                            <div class="text-sm mt-2">
                                                <?php echo wpautop(wp_kses_post($content)); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-2 items-center w-1/4 justify-center">
                                        <div class="render_flight_btn">
                                            <?php
                                            if ($is_package_included === 'yes'):
                                                echo '<span class="text-green-800">Package Included</span>';
                                            elseif ($is_selected):
                                                $helper_cls->delete_btn($hotel_id, 'excursions_id');
                                            else:
                                                $helper_cls->select_btn($hotel_id, 'excursions_id');
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
            echo "No Excursions Found";
        endif;

?>

<div class="mt-8 w-[1300px] px-5 mx-auto mb-8">
    <div class="select-none rounded-sm cursor-pointer text-white inline-block p-[10px_20px] bg-[#000] offer_tab_onclick" data-tabid="accommodations">
        <i class="fa fa-arrow-left"></i>
        <span>Accommodations</span>
    </div>
    <div class="select-none rounded-sm cursor-pointer text-white inline-block p-[10px_20px] bg-[#e73017] offer_tab_onclick" data-tabid="transport">
        <span>Transport</span>
        <i class="fa fa-arrow-right"></i>
    </div>
</div>
</div>