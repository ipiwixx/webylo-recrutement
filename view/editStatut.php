<?php

/**
 * /view/editStatut.php
 *
 * Page pour la modification d'un statut
 * @author A. Espinoza
 * @date 03/2023
 */

if (!isset($_SESSION['user']) || $exist == false) {
    header('Location: /erreur/');
} else {

    $title = 'Modification Statut | Webylo Recrutement';
    include_once 'header.php';

?>

    <div class="pEditS">
        <form class="row g-3 needs-validation m-4" method="POST" action="/statut/<?= $unStatut->getId() ?>/">
            <?= $mess ?>
            <div class="row text-center">
                <h1 class="mb-4">Détail du Statut n°<?= $unStatut->getId() ?></h1>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-5 my-2">
                    <div class="mb-3">
                        <label for="libelle" class="text-dark">Libelle</label>
                        <input type="text" name="libelle" class="form-control" id="libelle" value="<?= $unStatut->getLibelle() ?>" placeholder="Libelle" maxlength="64" required>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-2">
                    <button class="btnGreen editSubmit" data-id="<?= $unStatut->getId() ?>" name="editSubmit" type="submit">Enregistrer <i class='bx bx-save'></i></button>
                </div>
            </div>
        </form>
        <div class="container">
            <div class="row">
                <div class="col-lg-2 mb-3">
                    <a href="/statut/" class="offset-2 btnBlue"><i class='bx bx-undo'></i>&nbsp;Dashboard</a>
                </div>
                <div class="col-lg-2 mb-3" id="dStatut">
                    <button class="btnRed deleteSubmit" name="deleteSubmit" data-bs-toggle="modal" data-bs-target="#modalStatut" data-id="<?= $unStatut->getId() ?>">Supprimer <i class='bx bx-trash'></i></button>
                </div>
            </div>
        </div>
    </div>

    <div id="modalStatut" class="modal fade" role="dialog">
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
                    <p>Voulez-vous vraiment supprimer ce statut ? Cela entraînera la suppression de tous les candidats ayant le statut supprimé</p>
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
    <script type="text/javascript" src="/js/editStatut.js"></script>
    </body>

    </html>
<?php } ?>