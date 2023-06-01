<?php

/**
 * /view/addUtilisateur.php
 *
 * Page pour l'ajout d'un utilisateur
 * @author A. Espinoza
 * @date 03/2023
 */

if (!isset($_SESSION['user'])) {
    header('Location: /erreur/');
} else {

    $title = 'Ajout Utilisateur | Webylo Recrutement';
    include_once 'header.php';

?>

    <form class="row g-3 needs-validation m-4 py-5 pAddU" method="POST" action="/utilisateur/ajouter/">
        <?php if (!empty($mess)) { ?>
            <div class="text-center d-flex justify-content-center mt-5 pt-5">
                <?= $mess ?>
            </div>
        <?php } ?>
        <div class="row justify-content-center mt-5 pt-3">
            <h1 class="mb-4 text-center">Ajouter Utilisateur</h1>
            <div class="col-lg-5 ">
                <div class="mb-3">
                    <label for="nom">Nom</label>
                    <input type="text" name="nom" class="form-control" id="nom" pattern="^[A-Za-z]+$" placeholder="Nom" maxlength="64" required>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-5 ">
                <div class="mb-3">
                    <label for="prenom">Prénom</label>
                    <input type="text" name="prenom" class="form-control" id="prenom" pattern="^[a-zA-ZàâæçéèêëîïôœùûüÿÀÂÆÇnÉÈÊËÎÏÔŒÙÛÜŸ-]+" placeholder="Prénom" maxlength="64" required>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-5 ">
                <div class="mb-3">
                    <label for="email">Email</label>
                    <input type="email" name="email" class="form-control" id="email" placeholder="Email" maxlength="128" required>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-5 ">
                <div class="mb-3">
                    <label for="mdp">Mot de passe</label>
                    <input type="password" name="mdp" class="form-control" id="mdp" placeholder="Mot de passe" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" minlength="8" maxlength="128" TITLE="Le mot de passe doit contenir au moins 8 caractères composés d'au moins un chiffre et d'une lettre majuscule et minuscule." required>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-5 ">
                <div class="mb-3">
                    <label for="mdpc">Confirmer le mot de passe</label>
                    <input type="password" name="mdpc" class="form-control" id="mdpc" placeholder="Confirmer le mot de passe" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" minlength="8" maxlength="128" required>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-5 mb-3">
                <label for="role">Rôle</label>
                <select class="form-select" name="role" id="role" required>
                    <option value="" selected>Selectionner un rôle</option>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-2 ms-5">
                <button class="btnPink ms-5" name="addSubmit" type="submit">Ajouter</button>
            </div>
        </div>
        <div class="container">
            <a href="/utilisateur/" class="mt-5 offset-3 btnBlue"><i class='bx bx-undo'></i>&nbsp;Dashboard</a>
        </div>
    </form>

    <!-- Début footer -->
    <?php
    include_once 'footer.php';
    ?>
    <!-- Fin footer -->
    </body>

    </html>
<?php } ?>