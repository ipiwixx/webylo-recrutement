$("#enAtt").delegate(".actionsRef", "click", function (event) {
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

$("#enAtt").delegate(".actionsAcc", "click", function (event) {
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

$("#tPoste").delegate(".deletePoste", "click", function (event) {
  $("#confirm-delete").on("click", () => {
    var id = $(event.target).data("id");
    var url = "del/" + id + "/";
    $.ajax({
      url: url,

      type: "GET",

      success: function () {
        location.reload(true);
        setTimeout(function () {
          var element = document.getElementById("liveToast");
          var myToast = new bootstrap.Toast(element, {
            delay: 4000,
          });
          myToast.show();
          var element = document.getElementById("myprogressBar");
          var width = 100;
          var identity = setInterval(scene, 40);
          function scene() {
            width--;
            element.style.width = width + "%";
          }
        }, 1000); // Ajouter un délai de 1 seconde avant d'afficher le toast
      },
    });
  });
});

$("#tStatut").delegate(".deleteStatut", "click", function (event) {
  $("#confirm-delete").on("click", () => {
    var id = $(event.target).data("id");
    var url = "del/" + id + "/";
    $.ajax({
      url: url,

      type: "GET",

      success: function () {
        location.reload(true);
      },
    });
  });
});

$("#tUtilisateur").delegate(".deleteUtilisateur", "click", function (event) {
  $("#confirm-delete").on("click", () => {
    var id = $(event.target).data("id");
    var url = "del/" + id + "/";
    $.ajax({
      url: url,

      type: "GET",

      success: function () {
        location.reload(true);
      },
    });
  });
});

$("#tPoste").delegate(".offPoste", "click", function (event) {
  $("#confirm-desac").on("click", () => {
    var id = $(event.target).data("id");
    var url = "desac/" + id + "/";
    $.ajax({
      url: url,

      type: "GET",

      success: function () {
        location.reload(true);
      },
    });
  });
});

$("#tPoste").delegate(".onPoste", "click", function (event) {
  $("#confirm-reactiver").on("click", () => {
    var id = $(event.target).data("id");
    var url = "reac/" + id + "/";
    $.ajax({
      url: url,

      type: "GET",

      success: function () {
        location.reload(true);
      },
    });
  });
});

$("#ref").delegate(".delCandS", "click", function (event) {
  $("#confirm-delete").on("click", () => {
    var id = $(event.target).data("id");
    var url = "../vider/" + id + "/";
    $.ajax({
      url: url,

      type: "GET",

      success: function () {
        location.reload(true);
      },
    });
  });
});
