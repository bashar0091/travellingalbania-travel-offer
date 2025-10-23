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
$departure = get_post_meta(get_the_ID(), 'departure', true);
$return = get_post_meta(get_the_ID(), 'return', true);

if ($departure && $return) {
    $dep_date = new DateTime($departure);
    $ret_date = new DateTime($return);
    $diff_days = $dep_date->diff($ret_date)->days;
}
?>

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
                    <td><?php echo esc_html(date('j F, Y', strtotime($departure))); ?></td>
                </tr>
                <tr>
                    <th>Return:</th>
                    <td><?php echo esc_html(date('j F, Y', strtotime($return))); ?></td>
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
                    <td>€<?php echo wp_kses_post($price_per_person); ?></td>
                </tr>
                <tr>
                    <th>Total cost:</th>
                    <td>€<?php echo wp_kses_post($price_final); ?></td>
                </tr>
            </table>
        </div>

        <div class="render_summary_data">
            <?php
            $helper_cls->render_summary();
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