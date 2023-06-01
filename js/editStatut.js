var lesBtn = document.querySelectorAll(".editSubmit");
lesBtn.forEach((unBtn) => {
  unBtn.addEventListener("click", function (event) {
    var id = $(event.target).data("id");
    var url = "../" + id + "/";
    $.ajax({
      url: url,

      type: "GET",

      success: function () {
        location.reload();
      },
    });
  });
});

$("#dStatut").delegate(".deleteSubmit", "click", function (event) {
  $("#confirm-delete").on("click", () => {
    var id = $(event.target).data("id");
    var url = "../del/" + id + "/";
    $.ajax({
      url: url,

      type: "GET",

      success: function () {
        window.location = "../";
      },
    });
  });
});
