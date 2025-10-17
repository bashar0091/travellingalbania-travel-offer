<?php
$flight_terms = wp_get_post_terms($post_id, 'tta_travel_flights');

$helper_cls = new TravelAlbania_Init_Helper;
?>
<div>
    <p>Do you prefer a different flight? Let us know and we'll be happy to adjust your quote accordingly.</p>
    <h2 class="!mb-10">Please indicate your flight preference</h2>

    <?php
    if (!empty($flight_terms) && !is_wp_error($flight_terms)) :
        foreach ($flight_terms as $term) :
            $id = $term->term_id;
            $name = $term->name;
            $flight_info = get_term_meta($id, 'TravelAlbania_flight_repeat', true);

            $is_package_included = get_term_meta($id, 'is_package_included', true);
            $is_selected = in_array($id, $session_flights_id);
    ?>
            <div class="flex items-center gap-5 shadow-lg p-10 mb-10">
                <div class="w-3/4">
                    <h2 class="!mb-5"><?php echo wp_kses_post($name); ?></h2>

                    <table>
                        <tr>
                            <th></th>
                            <th>DATE</th>
                            <th>FROM - TO</th>
                            <th>FLIGHT NUMBER</th>
                            <th>CLASS</th>
                        </tr>
                        <?php
                        if (!empty($flight_info)):
                            foreach ($flight_info as $info):
                                $date = $info['date'];
                                $start_time = $info['start_time'];
                                $end_time = $info['end_time'];
                                $from = $info['from'];
                                $to = $info['to'];
                                $flight_number = $info['flight_number'];
                                $flight_class = $info['flight_class'];
                                $flight_logo = $info['flight_logo'];
                                $flight_name = $info['flight_name'];
                        ?>
                                <tr>
                                    <td>
                                        <div>
                                            <img class="w-[40px] mx-auto" src="<?php echo esc_url($flight_logo); ?>">
                                        </div>
                                        <div class="text-center">
                                            <?php echo wp_kses_post($flight_name); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php echo wp_kses_post($date . ' . ' . $start_time . ' - ' . $end_time); ?>
                                    </td>
                                    <td><?php echo wp_kses_post($from . ' - ' . $to); ?></td>
                                    <td><?php echo wp_kses_post($flight_number); ?></td>
                                    <td><?php echo wp_kses_post($flight_class); ?></td>
                                </tr>
                        <?php
                            endforeach;
                        endif; ?>
                    </table>

                </div>

                <div class="w-1/4 render_flight_btn">
                    <?php
                    if ($is_package_included === 'yes'):
                        echo '<b class="text-green-800">Package Included</b>';
                    elseif ($is_selected):
                        $helper_cls->delete_btn($id, 'flights_id');
                    else:
                        $helper_cls->select_btn($id, 'flights_id');
                    endif; ?>
                </div>
            </div>
    <?php
        endforeach;
    else:
        echo "No Flight Found";
    endif;
    ?>

</div>

<div>
    <span class="select-none cursor-pointer text-white inline-block p-[10px_20px] bg-[#000]" @click="setActive('program')">Program</span>
    <span class="select-none cursor-pointer text-white inline-block p-[10px_20px] bg-[#bf3d2a]" @click="setActive('accommodations')">Accommodations</span>
</div>