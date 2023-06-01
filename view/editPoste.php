<?php

/**
 * /view/editPoste.php
 *
 * Page pour la modification d'un poste
 * @author A. Espinoza
 * @date 03/2023
 */

if (!isset($_SESSION['user']) || $exist == false) {
    header('Location: /erreur/');
} else {

    $title = 'Modification Poste | Webylo Recrutement';
    include_once 'header.php';

?>

    <div class="pEditP">
        <form class="needs-validation m-4 py-5" method="POST" action="/poste/<?= $unPoste->getId() ?>/">
            <div class="text-center d-flex justify-content-center">
                <?= $mess ?>
            </div>
            <div class="row text-center">
                <h1 class="mb-4">Détail du Poste n°<?= $unPoste->getId() ?></h1>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-5 my-2">
                    <div class="mb-3">
                        <label for="libelle" class="text-dark">Libelle</label>
                        <input type="text" name="libelle" class="form-control" id="libelle" value="<?= $unPoste->getLibelle() ?>" placeholder="Libelle" maxlength="128" required>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-5 mb-2">
                    <div class="mb-3">
                        <label for="designation" class="text-dark">Désignation</label>
                        <input type="text" name="designation" class="form-control" id="designation" value="<?= $unPoste->getDesignation() ?>" placeholder="Désignation" maxlength="32" required>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-5 mb-2">
                    <div class="mb-3">
                        <label for="lienQuestion" class="text-dark">Lien du questionnaire</label>
                        <input type="text" name="lienQuestion" class="form-control" id="lienQuestion" value="<?= $unPoste->getLienQuestion() ?>" placeholder="Lien du questionnaire" maxlength="128">
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-2">
                    <button class="btnGreen editSubmit" data-id="<?= $unPoste->getId() ?>" name="editSubmit" type="submit">Enregistrer <i class='bx bx-save'></i></button>
                </div>
            </div>
        </form>
        <div class="container">
            <div class="row text-center">
                <div class="col-lg-2 mb-5">
                    <a href="/poste/" class="offset-2 btnBlue"><i class='bx bx-undo'></i>&nbsp;Dashboard</a>
                </div>
            </div>
            <div class="row text-center justify-content-center">
                <div class="col-lg-6 col-md-6 col-sm-6 col-6 mb-3" id="dPoste">
                    <button class="btnRed deleteSubmit" name="deleteSubmit" data-bs-toggle="modal" data-bs-target="#modalPoste" data-id="<?= $unPoste->getId() ?>">Supprimer <i class='bx bx-trash'></i></button>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-6 mb-3" id="oPoste">
                    <?php if ($unPoste->getDesactiver() == 0) { ?>
                        <button class="btnOrange offPoste" data-bs-toggle="modal" data-bs-target="#modalPosteOff" data-id="<?= $unPoste->getId() ?>">Désactiver <i class='bx bx-power-off'></i></button>
                    <?php } else { ?>
                        <button class="btnGreen onPoste" data-bs-toggle="modal" data-bs-target="#modalPosteOn" data-id="<?= $unPoste->getId() ?>">Réactiver <i class='bx bx-power-off'></i></button>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div id="modalPoste" class="modal fade" role="dialog">
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
                    <p>Voulez-vous vraiment supprimer ce poste ? Cela entraînera la suppression de tous les candidats ayant postulé pour ce poste</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-danger" id="confirm-delete">Supprimer</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modalPosteOff" class="modal fade" role="dialog">
        <div class="modal-dialog modal-confirm">
            <div class="modal-content">
                <div class="modal-header flex-column">
                    <div class="icon-box">
                        <i class='bx bx-power-off'></i>
                    </div>
                    <h4 class="modal-title w-100">Êtes-vous sûr ?</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Voulez-vous vraiment fermer le recrutement pour ce poste ? Cela entraînera la suppression de tous les candidats refusés pour ce poste</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-danger" id="confirm-desac">Désactiver</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modalPosteOn" class="modal fade" role="dialog">
        <div class="modal-dialog modal-confirm">
            <div class="modal-content">
                <div class="modal-header flex-column">
                    <div class="icon-boxA">
                        <i class='bx bx-power-off'></i>
                    </div>
                    <h4 class="modal-title w-100">Êtes-vous sûr ?</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Voulez-vous vraiment réouvrir le recrutement pour ce poste ?</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-success" id="confirm-reactiver">Réactiver</button>
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
    <script type="text/javascript" src="/js/editPoste.js"></script>
    </body>

    </html>
<?php } ?>