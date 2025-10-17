jQuery(document).ready(function ($) {
  var offer_total_price = $(".offer_total_price");
  var render_summary_data = $(".render_summary_data");
  var render_flight_btn = $(".render_flight_btn");

  // flight_on_select
  $(document).on("click", ".flight_on_select", function (e) {
    e.preventDefault();
    var t = $(this);
    var flight_id = t.data("flightid");
    var type = t.data("type");
    var offer_id = $('input[name="offer_id"]').val();

    $.ajax({
      type: "POST",
      url: local.ajax_url,
      data: {
        action: "get_flight_data",
        flight_id: flight_id,
        offer_id: offer_id,
        type: type,
      },
      success: function (response) {
        offer_total_price.text(response.data.total_price);
        render_summary_data.html(response.data.summary_content);
        t.parent().html(response.data.delete_btn);
        console.log("Response:", response);
      },
      error: function (xhr, status, error) {
        console.error("AJAX Error:", error);
      },
    });
  });

  // flight_on_select
  $(document).on("click", ".flight_on_delete", function (e) {
    e.preventDefault();
    var t = $(this);
    var flight_id = t.data("flightid");
    var type = t.data("type");
    var offer_id = $('input[name="offer_id"]').val();

    $.ajax({
      type: "POST",
      url: local.ajax_url,
      data: {
        action: "delete_flight_data",
        flight_id: flight_id,
        offer_id: offer_id,
        type: type,
      },
      success: function (response) {
        offer_total_price.text(response.data.total_price);
        render_summary_data.html(response.data.summary_content);
        t.parent().html(response.data.select_btn);
        console.log("Response:", response);
      },
      error: function (xhr, status, error) {
        console.error("AJAX Error:", error);
      },
    });
  });
});
