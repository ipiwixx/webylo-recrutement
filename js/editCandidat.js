$("#dCandidat").delegate(".deleteSubmit", "click", function (event) {
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

$("#statut").delegate(".actionsRef", "click", function (event) {
  $("#confirm-refuser").on("click", () => {
    var id = $(event.target).data("id");
    var url = "../refus/" + id + "/";
    $.ajax({
      url: url,

      type: "GET",

      success: function () {
        location.reload(true);
      },
    });
  });
});

$("#statut").delegate(".actionsAcc", "click", function (event) {
  $("#confirm-accepter").on("click", () => {
    var id = $(event.target).data("id");
    var url = "../accepté/" + id + "/";
    $.ajax({
      url: url,

      type: "GET",

      success: function () {
        location.reload(true);
      },
    });
  });
});

var lesBtn = document.querySelectorAll(".editSubmit");
lesBtn.forEach((unBtn) => {
  unBtn.addEventListener("click", function (event) {
    var id = $(event.target).data("id");
    var url = "../" + id + "/";
    $.ajax({
      url: url,

      type: "GET",

      success: function () {
        location.reload(true);
      },
    });
  });
});
