<style>
    .summary_table {
        border: none;
    }

    .summary_table td,
    .summary_table th {
        padding: 5px;
        text-align: left;
        border: none;
    }

    .summary_table th {
        width: 20%;
    }
</style>

<div class="mb-10">
    <div class="shadow-lg rounded-lg p-10">
        <table class="summary_table">
            <tr>
                <th>Name:</th>
                <td>Van der Veen Family</td>
            </tr>
            <tr>
                <th>Departure:</th>
                <td>November 3, 2025</td>
            </tr>
            <tr>
                <th>Return:</th>
                <td>November 14, 2025</td>
            </tr>
            <tr>
                <th>Duration:</th>
                <td>12 days</td>
            </tr>
            <tr>
                <th></th>
                <td></td>
            </tr>
            <tr>
                <th>Number of people:</th>
                <td>5</td>
            </tr>
            <tr>
                <th>Price per person:</th>
                <td>€1214.00</td>
            </tr>
            <tr>
                <th>Total cost:</th>
                <td>€6,070.00</td>
            </tr>
        </table>
    </div>

    <div class="render_summary_data">
        <?php
        $helper_cls->render_summary();
        ?>
    </div>

</div>

<div>
    <span class="select-none cursor-pointer text-white inline-block p-[10px_20px] bg-[#000] offer_tab_onclick" data-tabid="transport">Transport</span>
    <span class="select-none cursor-pointer text-white inline-block p-[10px_20px] bg-[#bf3d2a] offer_tab_onclick" data-tabid="book">Book</span>
</div>