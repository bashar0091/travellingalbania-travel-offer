jQuery(document).ready(function ($) {
  var offer_price_per_person = $(".offer_price_per_person");
  var offer_final_price = $(".offer_final_price");
  var render_summary_data = $(".render_summary_data");

  // flight_on_select
  $(document).on("click", ".flight_on_select", function (e) {
    e.preventDefault();
    var t = $(this);
    var flight_id = t.data("flightid");
    var type = t.data("type");
    var key = t.data("key");
    var offer_id = $('input[name="offer_id"]').val();

    $.ajax({
      type: "POST",
      url: local.ajax_url,
      data: {
        action: "get_flight_data",
        flight_id: flight_id,
        offer_id: offer_id,
        type: type,
        key: key,
      },
      success: function (response) {
        offer_price_per_person.text(response.data.price_per_person);
        offer_final_price.text(response.data.final_price);
        render_summary_data.html(response.data.summary_content);

        if (type == 'flights_id') {
          $('.render_flight_content').html(response.data.render_flight_content);
        } else if (type == 'accommodations_id') {
          $('.render_accommodation_content').html(response.data.render_flight_content);
        } else if (type == 'transports_id') {
          $('.render_transport_content').html(response.data.render_flight_content);
        } else {
          t.parent().html(response.data.delete_btn);
        }

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
        offer_price_per_person.text(response.data.price_per_person);
        offer_final_price.text(response.data.final_price);
        render_summary_data.html(response.data.summary_content);
        t.parent().html(response.data.select_btn);
      },
      error: function (xhr, status, error) {
        console.error("AJAX Error:", error);
      },
    });
  });

  //=====================
  $(document).on("click", ".offer_tab_onclick", function (e) {
    e.preventDefault();

    var t = $(this);
    var tabid = t.data("tabid");
    var offer_tab_top = $(".offer_tab_top");
    var offer_content_display = $(".offer_content_display");

    offer_tab_top.removeClass("active");
    offer_tab_top.filter(`[data-tabid="${tabid}"]`).addClass("active");

    var url = new URL(window.location.href);
    url.searchParams.set("active", tabid);

    window.history.replaceState(null, null, url.toString());

    offer_content_display.hide();
    $(`[data-showid="${tabid}"]`).show();
  });
});
