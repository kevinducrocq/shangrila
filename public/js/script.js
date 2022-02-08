$(".notifications").html();
setTimeout(function () {
  $(".alert-dismissible").fadeOut(600);
}, 2000);

jQuery(document).ready(function () {
  $("#choose_category").on("change", function () {
    if ($(this).value != 0) {
      var id = $(this).val();
      $.ajax({
        url: Routing.generate("display_food", { id: id }),
        type: "POST",
        beforeSend: function () {
          var loading = '<i class="fas fa-spinner fa-7x fa-pulse"></i>';
          $("#results-food").html(loading);
        },
        success: function (response, statut) {
          $("#results-food").html(response.response);
        },
      });
    } else {
      $("#results-food").html("");
    }
  });
});

// Carousel

