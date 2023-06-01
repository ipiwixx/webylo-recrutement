<?php

/**
 * /view/editUtilisateur.php
 *
 * Page pour la modification d'un utilisateur
 * @author A. Espinoza
 * @date 03/2023
 */

if (!isset($_SESSION['user']) || $exist == false) {
    header('Location: /erreur/');
} else {

    $title = 'Modification Utilisateur | Webylo Recrutement';
    include_once 'header.php';

?>

    <form class="row g-3 needs-validation m-4 pt-5" method="POST" action="/utilisateur/<?= $unUtilisateur->getId() ?>/">
        <div class="row mt-5 pt-5 justify-content-center">
            <?= $mess ?>
            <?= $messMdp ?>
            <h1 class="mb-4 text-center">Détail de l'Utilisateur n°<?= $unUtilisateur->getId() ?></h1>
            <div class="col-lg-5 my-2">
                <div class="mb-3">
                    <label for="nom" class="text-dark">Nom</label>
                    <input type="text" name="nom" class="form-control" id="nom" pattern="^[A-Za-z]+$" value="<?= $unUtilisateur->getNom() ?>" placeholder="Nom" maxlength="64" required>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-5 mb-2">
                <div class="mb-3">
                    <label for="prenom" class="text-dark">Prénom</label>
                    <input type="text" name="prenom" class="form-control" id="prenom" pattern="^[a-zA-ZàâæçéèêëîïôœùûüÿÀÂÆÇnÉÈÊËÎÏÔŒÙÛÜŸ-]+" value="<?= $unUtilisateur->getPrenom() ?>" placeholder="Prénom" maxlength="64" required>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-5 mb-2">
                <div class="mb-3">
                    <label for="email" class="text-dark">Email</label>
                    <input type="email" name="email" class="form-control" id="email" value="<?= $unUtilisateur->getEmail() ?>" placeholder="Email" maxlength="128" required>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-5 mb-3">
                <?php if ($_SESSION['user']->getRole() == 'admin') {
                    if ($unUtilisateur->getRole() != 'admin') { ?>
                        <label for="role" class="text-dark">Rôle</label>
                        <select id="role" class="form-select" name="role" required>
                            <option value="<?= $unUtilisateur->getRole() ?>" selected><?= $unUtilisateur->getRole() ?></option>
                            <?php if ($unUtilisateur->getRole() == 'admin') { ?>
                                <option value="user">user</option>
                            <?php } else { ?>
                                <option value="admin">admin</option>
                            <?php } ?>
                        </select>
                    <?php } else { ?>
                        <label for="role" class="text-dark">Rôle</label>
                        <select id="role" class="form-select" name="role" disabled>
                            <option value="<?= $unUtilisateur->getRole() ?>" selected><?= $unUtilisateur->getRole() ?></option>
                        </select>
                    <?php }
                } else { ?>
                    <label for="role" class="text-dark">Rôle</label>
                    <select id="role" class="form-select" name="role" disabled>
                        <option value="<?= $unUtilisateur->getRole() ?>" selected><?= $unUtilisateur->getRole() ?></option>
                    </select>
                <?php } ?>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-2">
                <button class="btnGreen editSubmit" data-id="<?= $unUtilisateur->getId() ?>" name="editSubmit" type="submit">Enregistrer <i class='bx bx-save'></i></button>
            </div>
        </div>
        <div class="container mt-4">
            <hr>
        </div>
    </form>
    <form class="row g-3 m-4 pb-5" method="POST" action="/utilisateur/<?= $unUtilisateur->getId() ?>/">
        <h1 class="mb-4 text-center">Gestion mot de passe</h1>
        <div class="row justify-content-center">
            <div class="col-lg-5 my-2">
                <div class="mb-3 lblU">
                    <label for="mdp" class="text-dark">Ancien mot de passe</label>
                    <input type="password" name="mdp" class="form-control old" id="mdp" placeholder="Ancien mot de passe" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" minlength="8" maxlength="128" TITLE="Le mot de passe doit contenir au moins 8 caractères composés d'au moins un chiffre et d'une lettre majuscule et minuscule.">
                    <div class="password-icon">
                        <i class='bx bx-show oldE'></i>
                        <i class='bx bx-low-vision oldEO'></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-5 mb-2">
                <div class="mb-3 lblU">
                    <label for="newMdp" class="text-dark">Nouveau mot de passe</label>
                    <input type="password" name="newMdp" class="form-control new" id="newMdp" placeholder="Nouveau mot de passe" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" minlength="8" maxlength="128">
                    <div class="password-icon">
                        <i class='bx bx-show newE'></i>
                        <i class='bx bx-low-vision newEO'></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-2">
                <button class="btnGreen" data-id="<?= $unUtilisateur->getId() ?>" name="editSubmitP" type="submit">Enregistrer <i class='bx bx-save'></i></button>
            </div>
        </div>
    </form>
    <div class="container">
        <div class="row">
            <div class="col-lg-2 mb-3">
                <a href="/utilisateur/" class="offset-2 btnBlue"><i class='bx bx-undo'></i>&nbsp;Dashboard</a>
            </div>
            <div class="col-lg-2 mb-3" id="dUtilisateur">
                <button class="btnRed deleteSubmit" name="deleteSubmit" data-bs-toggle="modal" data-bs-target="#modalUtilisateur" data-id="<?= $unUtilisateur->getId() ?>">Supprimer <i class='bx bx-trash'></i></button>
            </div>
        </div>
    </div>

    <div id="modalUtilisateur" class="modal fade" role="dialog">
        <div class="modal-dialog modal-confirm">
            <div class="modal-content">
                <div class="modal-header flex-column">
                    <div class="icon-box">
                        <i class="bx bx-trash"></i>
                    </div>
                    <h4 class="modal-title w-100">Êtes-vous sûr ?</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Voulez-vous vraiment supprimer cet utilisateur ?</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-danger" id="confirm-delete">Supprimer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Début footer -->
    <?php
    include_once 'footer.php';
    ?>
    <!-- Fin footer -->

    <!-- JS Libraries -->
    <script type="text/javascript" src="/js/editUtilisateur.js"></script>
    </body>

    </html>
<?php } ?>