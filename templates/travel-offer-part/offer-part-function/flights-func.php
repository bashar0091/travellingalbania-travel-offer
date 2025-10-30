<?php
function render_flight_content($post_id, $helper_cls, $session_flights_id)
{
    $flight_select = get_post_meta($post_id, 'flight_select', true);

    $flight_departure_date = gmdate('d/m/Y', strtotime(get_post_meta($post_id, 'flight_departure_date', true)));
    $flight_departure_start_time = gmdate('H:i', strtotime(get_post_meta($post_id, 'flight_departure_start_time', true)));
    $flight_departure_end_time = gmdate('H:i', strtotime(get_post_meta($post_id, 'flight_departure_end_time', true)));

    $flight_return_date = gmdate('d/m/Y', strtotime(get_post_meta($post_id, 'flight_return_date', true)));
    $flight_return_start_time = gmdate('H:i', strtotime(get_post_meta($post_id, 'flight_return_start_time', true)));
    $flight_returne_end_time = gmdate('H:i', strtotime(get_post_meta($post_id, 'flight_returne_end_time', true)));

    if (!empty($flight_select) && is_array($flight_select)) :
        foreach ($flight_select as $flight_id) :
            $term = get_term($flight_id);
            $id = $term->term_id;
            $name = $term->name;
            $flight_info = get_term_meta($id, 'TravelAlbania_flight_repeat', true);
            $price = get_term_meta($id, 'price', true);

            $is_package_included = get_term_meta($id, 'is_package_included', true);
            $is_selected = in_array($id, $session_flights_id);

            $flight_includes = get_term_meta($id, 'flight_includes', true);
?>
            <div class="fly-table flex items-center rounded-lg ring ring-[#80808012] shadow-md justify-center gap-5 mb-10 p-10">
                <div class="border-r-1 border-[#80808057] w-2/3 pr-10">
                    <h2 class="!mb-5 pt-10 !text-lg"><?php echo wp_kses_post($name); ?></h2>

                    <table class="!border-none pb-50">
                        <tr>
                            <th class="!border-none !bg-transparent text-left"></th>
                            <th class="!border-none !bg-transparent text-left">DATE</th>
                            <th class="!border-none !bg-transparent text-left">FROM - TO</th>
                            <th class="!border-none !bg-transparent text-left">FLIGHT NUMBER</th>
                            <th class="!border-none !bg-transparent text-left">CLASS</th>
                        </tr>
                        <?php
                        if (!empty($flight_info)):
                            $total_rows = count($flight_info);
                            $i = 0;
                            foreach ($flight_info as $index => $info):
                                $i++;
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
                                            <div class="font-bold text-[12px]">
                                                <?php echo wp_kses_post($flight_name); ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($i == 1) {
                                            echo wp_kses_post($flight_departure_date . ' . ' . $flight_departure_start_time . ' - ' . $flight_departure_end_time);
                                        } elseif ($i == 2) {
                                            echo wp_kses_post($flight_return_date . ' . ' . $flight_return_start_time . ' - ' . $flight_returne_end_time);
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo wp_kses_post($from . ' - ' . $to); ?></td>
                                    <td><?php echo wp_kses_post($flight_number); ?></td>
                                    <td><?php echo wp_kses_post($flight_class); ?></td>
                                </tr>
                        <?php
                            endforeach;
                        endif; ?>
                    </table>

                    <?php if (!empty($flight_includes)): ?>
                        <div class="flex items-center flex-wrap gap-5 mt-10 w-full">
                            <?php
                            foreach ($flight_includes as $item):
                                $term = get_term($item);
                                $id_icn = $term->term_id;
                                $name = $term->name;
                                $des = $term->description;
                                $icon = get_term_meta($id_icn, 'icon', true);
                            ?>
                                <div class="flex gap-2">
                                    <div>
                                        <img src="<?php echo esc_url($icon); ?>" class="w-[15px]">
                                    </div>
                                    <div class="flex-1">
                                        <h6 class="!m-0 !text-[14px]"><?php echo wp_kses_post($name); ?></h6>
                                        <!-- <p class="!m-0 text-[14px]"><?php //echo wp_kses_post($des); 
                                                                            ?></p> -->
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="flex flex-col gap-2 items-center w-1/3 justify-center">
                    <div class="render_flight_btn">
                        <?php
                        if ($is_selected):
                            $helper_cls->delete_btn($id, 'flights_id', 'Choosen', 'not_delete');
                        else:
                            $helper_cls->select_btn($id, 'flights_id');
                        endif; ?>
                    </div>

                    <?php if (!$is_selected): ?>
                        <span>
                            €
                            <?php
                            $different_price = $helper_cls->showing_price_different($post_id, 'flights_id', $id);

                            if ($different_price == 0) {
                                echo wp_kses_post(number_format((float)$price, 2));
                            } else {
                                echo $different_price;
                            }
                            ?> / Person
                        </span>
                    <?php endif; ?>
                </div>
            </div>
<?php
        endforeach;
    else:
        echo "No Flight Found";
    endif;
}
