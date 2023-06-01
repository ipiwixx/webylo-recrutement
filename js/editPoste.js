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

$("#dPoste").delegate(".deleteSubmit", "click", function (event) {
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

$("#oPoste").delegate(".offPoste", "click", function (event) {
  $("#confirm-desac").on("click", () => {
    var id = $(event.target).data("id");
    var url = "../desac/" + id + "/";
    $.ajax({
      url: url,

      type: "GET",

      success: function () {
        location.reload(true);
      },
    });
  });
});

$("#oPoste").delegate(".onPoste", "click", function (event) {
  $("#confirm-reactiver").on("click", () => {
    var id = $(event.target).data("id");
    var url = "../reac/" + id + "/";
    $.ajax({
      url: url,

      type: "GET",

      success: function () {
        location.reload(true);
      },
    });
  });
});
