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

$("#dUtilisateur").delegate(".deleteSubmit", "click", function (event) {
  //$('body').append('<div id="modalUtilisateur" class="modal fade"><div class="modal-dialog modal-confirm"><div class="modal-content"><div class="modal-header flex-column"><div class="icon-box"><i class="bx bx-trash"></i></div><h4 class="modal-title w-100">Êtes-vous sûr ?</h4><button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">&times;</button></div><div class="modal-body"><p>Voulez-vous vraiment supprimer cet utilisateur ?</p></div><div class="modal-footer justify-content-center"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button><button type="button" class="btn btn-danger" data-id="<?= $unUtilisateur->getId() ?>" id="confirm-delete">Supprimer</button></div></div></div></div>')
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

window.onload = (event) => {
  const eye = document.querySelector(".oldE");
  const eyeoff = document.querySelector(".oldEO");
  const passwordField = document.querySelector('.old[type="password"]');

  eye.addEventListener("click", () => {
    eye.style.display = "none";
    eyeoff.style.display = "block";
    passwordField.type = "text";
  });

  eyeoff.addEventListener("click", () => {
    eyeoff.style.display = "none";
    eye.style.display = "block";
    passwordField.type = "password";
  });

  const eye2 = document.querySelector(".newE");
  const eyeoff2 = document.querySelector(".newEO");
  const passwordField2 = document.querySelector('.new[type="password"]');

  eye2.addEventListener("click", () => {
    eye2.style.display = "none";
    eyeoff2.style.display = "block";
    passwordField2.type = "text";
  });

  eyeoff2.addEventListener("click", () => {
    eyeoff2.style.display = "none";
    eye2.style.display = "block";
    passwordField2.type = "password";
  });
};
