<style>
    .summary_table {
        border: none;
    }

    .summary_table td,
    .summary_table th {
        padding: 5px;
        text-align: left;
        border: none;
        background-color: transparent !important;
    }

    .summary_table th {
        width: 20%;
    }
</style>

<?php
$departure_data = get_post_meta(get_the_ID(), 'departure', true);
$return_data = get_post_meta(get_the_ID(), 'return', true);

$diff_days = 0;

if (!empty($departure_data) && !empty($return_data)) {
    $dep_date = new DateTime();
    $dep_date->setTimestamp($departure_data);

    $ret_date = new DateTime();
    $ret_date->setTimestamp($return_data);

    $interval = $ret_date->diff($dep_date);
    $diff_days = ($ret_date < $dep_date) ? 0 : $interval->days;
}

$departure = !empty($departure_data) ? date('d/m/Y', $departure_data) : '';
$return = !empty($return_data) ? date('d/m/Y', $return_data) : '';
?>

<div class="overflow-hidden">
    <div class="max-w-[1300px] mx-auto px-5 pt-10">
        <div class="mb-10">
            <div class="shadow-md p-3 mb-3 rounded-lg ring ring-[#80808012] shadow-m">
                <table class="summary_table">
                    <tr>
                        <th>Name:</th>
                        <td><?php echo esc_html(get_the_title()); ?></td>
                    </tr>
                    <tr>
                        <th>Departure:</th>
                        <td><?php echo esc_html(date('j F, Y', $departure_data)); ?></td>
                    </tr>
                    <tr>
                        <th>Return:</th>
                        <td><?php echo esc_html(date('j F, Y', $return_data)); ?></td>
                    </tr>
                    <tr>
                        <th>Duration:</th>
                        <td><?php echo esc_html($diff_days) ?> days</td>
                    </tr>
                    <tr>
                        <th></th>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Number of people:</th>
                        <td><?php echo esc_html($people) ?></td>
                    </tr>
                    <tr>
                        <th>Price per person:</th>
                        <td>€<span class="offer_price_per_person"><?php echo wp_kses_post($price_per_person); ?></span></td>
                    </tr>

                    <?php
                    $extra_cost_select = get_post_meta($post_id, 'extra_cost_select', true);
                    if (!empty($extra_cost_select)):
                    ?>
                        <tr>
                            <td colspan="2">
                                <hr class="w-[30%]">
                            </td>
                        </tr>
                        <?php
                        foreach ($extra_cost_select as $cost):
                            $term = get_term($cost);
                            $title = $term->name;
                            $price = get_term_meta($cost, 'price', true);
                        ?>
                            <tr>
                                <th><?php echo wp_kses_post($title); ?>:</th>
                                <td>+ €<span><?php echo wp_kses_post($price); ?></span></td>
                            </tr>
                        <?php
                        endforeach;
                        ?>
                        <tr>
                            <td colspan="2">
                                <hr class="w-[30%]">
                            </td>
                        </tr>
                    <?php
                    endif;
                    ?>

                    <tr>
                        <th>Total cost:</th>
                        <td>€<span class="offer_final_price"><?php echo wp_kses_post($price_final); ?></span></td>
                    </tr>
                </table>
            </div>

            <div class="render_summary_data">
                <?php
                $helper_cls->render_summary($post_id);
                ?>
            </div>

        </div>

        <div class="mt-8 w-[1300px] mx-auto mb-8">
            <div class="select-none cursor-pointer rounded-sm text-white inline-block p-[10px_20px] bg-[#000] offer_tab_onclick" data-tabid="transport">
                <i class="fa fa-arrow-left"></i>
                <span>Transport</span>
            </div>
            <div class="select-none cursor-pointer rounded-sm text-white inline-block p-[10px_20px] bg-[#e73017] offer_tab_onclick" data-tabid="book">
                <span>Book</span>
                <i class="fa fa-arrow-right"></i>
            </div>
        </div>
    </div>
</div>