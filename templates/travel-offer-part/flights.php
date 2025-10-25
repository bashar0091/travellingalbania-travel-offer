<?php
$flight_terms = wp_get_post_terms($post_id, 'tta_travel_flights');

$helper_cls = new TravelAlbania_Init_Helper;

if (has_post_thumbnail()) {
    $post_thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
} else {
    $post_thumbnail = plugin_dir_url(__FILE__) . "../assets/img/thumbnail.jpg";
}

$post_thumbnail = plugin_dir_url(dirname(__FILE__, 2)) . 'assets/img/thumbnail.jpg';
?>

<div>
    <div>
        <div class="bg-[url('<?php echo esc_url($post_thumbnail_url); ?>')] bg-cover bg-center w-full">
            <div class="flex flex-col justify-end items-start px-5 w-[1300px] gap-0 text-white h-64 mx-auto">
                <p class="!text-3xl font-bold">Best of Albania</p>
                <h2 class="!font-[Bebas Neue] !text-5xl !font-normal !mb-10">FLIGHTS</h2>
            </div>
        </div>
        <div class="!text-sm mt-10 w-full flex flex-col justify-end item-end px-5 !w-[1300px] gap-[0px] text-black mx-auto">
            <p>Do you prefer a different flight? Let us know and we'll be happy to adjust your quote accordingly.</p>
            <h2 class="!text-xl !mb-10">Please indicate your flight preference</h2>
        </div>

        <div class="px-5 w-[1300px] mx-auto">
            <?php
            if (!empty($flight_terms) && !is_wp_error($flight_terms)) :
                foreach ($flight_terms as $term) :
                    $id = $term->term_id;
                    $name = $term->name;
                    $flight_info = get_term_meta($id, 'TravelAlbania_flight_repeat', true);
                    $price = get_term_meta($id, 'price', true);

                    $is_package_included = get_term_meta($id, 'is_package_included', true);
                    $is_selected = in_array($id, $session_flights_id);
            ?>
                    <div class="fly-table flex items-center rounded-lg ring ring-[#80808012] shadow-md justify-center gap-5 mb-10">
                        <div class="border-r-1 border-[#80808057] pr-10 w-[65%]">
                            <h2 class="!mb-5 pt-10 !text-lg"><?php echo wp_kses_post($name); ?></h2>

                            <table class="!border-none pb-50">
                                <tr>
                                    <th class="!border-none !bg-transparent text-left"></th>
                                    <!-- <th class="!border-none !bg-transparent text-left">DATE</th> -->
                                    <th class="!border-none !bg-transparent text-left">FROM - TO</th>
                                    <th class="!border-none !bg-transparent text-left">FLIGHT NUMBER</th>
                                    <th class="!border-none !bg-transparent text-left">CLASS</th>
                                </tr>
                                <?php
                                if (!empty($flight_info)):
                                    $total_rows = count($flight_info);
                                    foreach ($flight_info as $index => $info):
                                        $date = $info['date'];
                                        $start_time = $info['start_time'];
                                        $end_time = $info['end_time'];
                                        $from = $info['from'];
                                        $to = $info['to'];
                                        $flight_number = $info['flight_number'];
                                        $flight_class = $info['flight_class'];
                                        $flight_logo = !empty($info['flight_logo']) ? $info['flight_logo'] : '';
                                        $flight_name = $info['flight_name'];
                                        // Check if this is the last row
                                        $tr_class = ($index !== $total_rows - 1) ? 'lagaborder' : 'lagasna';

                                ?>
                                        <tr class="<?php echo $tr_class; ?>">
                                            <td>
                                                <?php if (!empty($flight_logo)): ?>
                                                    <div>
                                                        <img class="w-[40px] mx-auto" src="<?php echo esc_url($flight_logo); ?>">
                                                    </div>
                                                    <div class="font-bold text-[12px] text-center">
                                                        <?php echo wp_kses_post($flight_name); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </td>

                                            <?php if (false): ?>
                                                <td>
                                                    <?php echo wp_kses_post($date . ' . ' . $start_time . ' - ' . $end_time); ?>
                                                </td>
                                            <?php endif; ?>

                                            <td><?php echo wp_kses_post($from . ' - ' . $to); ?></td>
                                            <td><?php echo wp_kses_post($flight_number); ?></td>
                                            <td><?php echo wp_kses_post($flight_class); ?></td>
                                        </tr>
                                <?php
                                    endforeach;
                                endif; ?>
                            </table>

                        </div>
                        <div class="flex flex-col gap-2 items-center w-1/4 justify-center">
                            <div class="render_flight_btn">
                                <?php
                                if ($is_package_included === 'yes'):
                                    echo '<span class="text-green-800">Package Included</span>';
                                elseif ($is_selected):
                                    $helper_cls->delete_btn($id, 'flights_id');
                                else:
                                    $helper_cls->select_btn($id, 'flights_id');
                                endif; ?>
                            </div>

                            <span>
                                <?php echo $is_package_included !== 'yes' && $price ? "+" : ""; ?>
                                €
                                <?php echo wp_kses_post(number_format((float)$price, 2)); ?> / Person
                            </span>
                        </div>
                    </div>
            <?php
                endforeach;
            else:
                echo "No Flight Found";
            endif;
            ?>
        </div>

        <div class="px-5 mt-8 w-[1300px] mx-auto">
            <div class="select-none rounded-sm cursor-pointer text-white inline-block p-[10px_20px] bg-[#000] offer_tab_onclick" data-tabid="program">
                <i class="fa fa-arrow-left"></i>
                <span>Program</span>
            </div>
            <div class="select-none rounded-sm cursor-pointer text-white inline-block p-[10px_20px] bg-[#e73017] offer_tab_onclick" data-tabid="accommodations">
                <span>Accommodations</span>
                <i class="fa fa-arrow-right"></i>
            </div>
        </div>
    </div>
</div>