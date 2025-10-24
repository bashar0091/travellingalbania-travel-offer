<div class="max-w-[1300px] mx-auto px-5 pt-10">
    <div class="mb-10">
        <div class="rounded-lg p-10 shadow-md ring ring-[#80808012]">
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
                    <td><?php echo esc_html($people) ?></td>
                </tr>
                <tr>
                    <th>Price per person:</th>
                    <td>€<span class="offer_price_per_person"><?php echo wp_kses_post($price_per_person); ?></span></td>
                </tr>
                <tr>
                    <th>Total cost:</th>
                    <td>€<span class="offer_final_price"><?php echo wp_kses_post($price_final); ?></span></td>
                </tr>
            </table>
        </div>
    </div>

    <div>
        <div class="select-none cursor-pointer rounded-sm text-white inline-block p-[10px_20px] bg-[#000] offer_tab_onclick" data-tabid="summary">
            <i class="fa fa-arrow-left"></i>
            <span>Summary</span>
        </div>
        <button type="submit" name="offer_booking_submit" class="rounded-sm !p-[11px_20px] !text-[13px] select-none cursor-pointer !text-white inline-block p-[10px_20px] !bg-[#e73017]">Confirm Booking <i class="fa fa-arrow-right"></i></button>
    </div>
</div>