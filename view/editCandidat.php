<?php

/**
 * /view/editCandidat.php
 *
 * Page de modification d'un candidat
 *
 * @author A. Espinoza
 * @date 03/2023
 */

if (!isset($_SESSION['user']) || $exist == false) {
    header('Location: /erreur/');
} else {

    $title = 'Détail Candidat | Webylo Recrutement';
    include_once 'header.php';

?>

    <div class="container my-5" id="frm">
        <form class="row g-3 needs-validation m-4 pt-5" id="frmP" method="POST" action="/candidat/<?= $unCandidat->getId() ?>/">
            <?= $mess ?>
            <h1 class="d-flex justify-content-center pt-5 my-4">Détail du candidat n°<?= $unCandidat->getId() ?></h1>
            <div class="row">
                <div class="col-lg-6">
                    <div class="infoPerso mt-3">
                        <h2 class="offset-1">Informations personnelles</h2>
                        <div class="offset-2 col-lg-8 my-2">
                            <div class="mb-3">
                                <label for="nom" class="text-dark">Nom</label>
                                <input type="text" name="nom" class="form-control" id="nom" pattern="^[A-Za-z]+$" value="<?= $unCandidat->getNom() ?>" placeholder="Nom" maxlength="64" required>
                            </div>
                        </div>
                        <div class="offset-2 col-lg-8 mb-2">
                            <div class="mb-3">
                                <label for="prenom" class="text-dark">Prénom</label>
                                <input type="text" name="prenom" class="form-control" id="prenom" pattern="^[a-zA-ZàâæçéèêëîïôœùûüÿÀÂÆÇnÉÈÊËÎÏÔŒÙÛÜŸ-]+" value="<?= $unCandidat->getPrenom() ?>" placeholder="Prénom" maxlength="64" required>
                            </div>
                        </div>
                        <div class="offset-2 col-lg-8 mb-2">
                            <div class="mb-3">
                                <label for="email" class="text-dark">Email</label>
                                <input type="email" name="email" class="form-control" id="email" value="<?= $unCandidat->getEmail() ?>" placeholder="Email" maxlength="128" required>
                            </div>
                        </div>
                        <div class="offset-2 col-lg-8 mb-2">
                            <div class="mb-3">
                                <label for="tel" class="text-dark">Numéro de téléphone</label>
                                <input type="tel" name="tel" class="form-control" id="tel" value="0<?= $unCandidat->getNumTel() ?>" placeholder="Numéro de téléphone" pattern="[0]{1}[0-9]{9}" minlength="10" maxlength="10" required>
                            </div>
                        </div>
                        <div class="offset-2 col-lg-8 my-2">
                            <div class="mb-3">
                                <label for="adresse" class="text-dark">Adresse</label>
                                <input type="text" name="adresse" class="form-control" id="adresse" value="<?= $unCandidat->getAdresse() ?>" placeholder="Adresse" minlength="12" maxlength="128" required>
                            </div>
                        </div>
                        <div class="offset-2 col-lg-8 mb-2">
                            <div class="mb-3">
                                <label for="ville" class="text-dark">Ville</label>
                                <input type="text" name="ville" class="form-control" id="ville" value="<?= $unCandidat->getVille() ?>" placeholder="Ville" minlength="3" maxlength="64" required>
                            </div>
                        </div>
                        <div class="offset-2 col-lg-8 mb-2">
                            <div class="mb-3">
                                <label for="cp" class="text-dark">Code postal</label>
                                <input type="text" name="cp" class="form-control" id="cp" value="<?= $unCandidat->getCp() ?>" placeholder="Code Postale" pattern="[0-9]{5}" minlength="5" maxlength="5" required>
                            </div>
                        </div>
                        <div class="offset-2 col-lg-8 mb-2">
                            <div class="mb-3">
                                <label for="dateN" class="text-dark">Date de naissance</label>
                                <input type="date" name="date" class="form-control" id="dateN" value="<?= $unCandidat->getDateNaissance()->format('Y-m-d') ?>" min="1945-01-01" max="2008-01-01" required>
                            </div>
                        </div>
                        <div class="offset-2 col-lg-3">
                            <button class="btnGreen editSubmit" data-id="<?= $unCandidat->getId() ?>" name="editSubmit" type="submit">Enregistrer <i class='bx bx-save'></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="infoComp mt-3">
                        <h2 class="offset-1 text-light">Informations complémentaires</h2>
                        <div class="offset-2 col-lg-8 my-2">
                            <div class="mb-3">
                                <?php $posteC = $unCandidat->getIdPoste();
                                $poste = PosteManager::getPosteById($posteC);
                                $key = array_search($poste, $lesPostes);
                                if ($key !== false) {
                                    unset($lesPostes[$key]);
                                } ?>
                                <label class="text-light">Poste à pourvoir</label>
                                <select class="form-select" name="poste" disabled>
                                    <option value="<?= $unCandidat->getIdPoste() ?>" selected><?= $unCandidat->getDesiPoste() ?></option>
                                    <?php foreach ($lesPostes as $unPoste) { ?>
                                        <option value="<?= $unPoste->getId() ?>"><?= $unPoste->getDesignation() ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="offset-2 col-lg-8 mb-2">
                            <div class="mb-3">
                                <label for="dateI" class="text-light">Date d'inscription</label>
                                <input type="date" name="dateI" disabled class="form-control" id="dateI" value="<?= $unCandidat->getDateInscription()->format('Y-m-d') ?>" min="1945-01-01" max="2008-01-01" required>
                            </div>
                        </div>
                        <div class="offset-2 col-lg-8 mt-4 my-2">
                            <div class="mb-3" id="statut">
                                <p class="<?= $unCandidat->getLibStatut() ?>"><?= $unCandidat->getLibStatut() ?></p>
                                <?php if ($unCandidat->getIdStatut() == 1) { ?>
                                    <button type="button" data-id="<?= $unCandidat->getId() ?>" data-bs-toggle="modal" data-bs-target="#modalCandA" class="btnGreen actionsAcc mt-3">Accepter <i class='bx bx-check-circle'></i></button><button data-id="<?= $unCandidat->getId() ?>" data-bs-toggle="modal" data-bs-target="#modalCandR" type="button" class="btnRed actionsRef ms-3 mt-3">Refuser <i class='bx bx-x-circle'></i></button>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="offset-2 col-lg-8 mb-2">
                            <div class="mb-3">
                                <label class="text-light">Cv</label><br>
                                <?php if ($unCandidat->getCv() != null) {
                                    if (file_exists($unCandidat->getCv())) {
                                        rename($unCandidat->getCv(), 'cv/' . $unCandidat->getNom() . '_' . $unCandidat->getPrenom() . '_' . $unCandidat->getId() . '.pdf');
                                    }
                                ?>
                                    <a href="/cv/<?= $unCandidat->getNom() . '_' . $unCandidat->getPrenom() . '_' . $unCandidat->getId() ?>.pdf" target="_blank" class="links">Voir le CV</a>
                                <?php } ?>
                            </div>
                        </div>
                        <?php if ($unCandidat->getFichiersComplementaires() != null) { ?>

                            <div class="offset-2 col-lg-8 mb-2">
                                <div class="mb-3">
                                    <label class="text-light">Fichiers Complémentaires</label><br>
                                    <?php
                                    $data = json_decode($unCandidat->getFichiersComplementaires(), true);
                                    $i = 0;
                                    foreach ($data as $unFichier) {
                                        foreach ($unFichier as $f) {
                                            $extensionFC_upload = strtolower(substr(strrchr($f, '.'), 1));
                                            if (file_exists($f)) {
                                                rename($f, 'fichiersComp/' . $unCandidat->getNom() . '_' . $unCandidat->getPrenom() . '_' . $unCandidat->getId() . '-' . $i . '.' . $extensionFC_upload);
                                            }
                                    ?>
                                            <a href="/fichiersComp/<?= $unCandidat->getNom() . '_' . $unCandidat->getPrenom() . '_' . $unCandidat->getId() . '-' . $i . '.' . $extensionFC_upload ?>" target="_blank" class="links">Voir le fichiers complémentaires</a><br>
                                            <?php $i++; ?>
                                    <?php }
                                    } ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="infoNote mt-4">
                        <p class="offset-1 text-light">Note /20 à attribuer en fonction du CV et du questionnaire </p>
                        <div class="offset-2 col-lg-8 mb-2">
                            <div class="mb-3">
                                <label for="note" class="text-dark">Note</label>
                                <input type="number" name="note" class="form-control" id="note" value="<?= $unCandidat->getNote() ?>" placeholder="Note /20" min="0" max="20">
                            </div>
                        </div>
                        <div class="offset-2 col-lg-3">
                            <button class="btnGreen editSubmit" data-id="<?= $unCandidat->getId() ?>" name="editSubmit" type="submit">Enregistrer <i class='bx bx-save'></i></button>
                        </div>
                    </div>
                </div>
            </div>

        </form>

        <div class="row">
            <div class="col-lg-2 mb-3">
                <a href="/candidat/" class="offset-2 btnBlue"><i class='bx bx-undo'></i>&nbsp;Dashboard</a>
            </div>
            <div class="col-lg-2 mb-3" id="dCandidat">
                <button class="btnRed deleteSubmit" name="deleteSubmit" data-bs-toggle="modal" data-bs-target="#modalCandidat" data-id="<?= $unCandidat->getId() ?>">Supprimer <i class='bx bx-trash'></i></button>
            </div>
        </div>
    </div>

    <div id="modalCandidat" class="modal fade" role="dialog">
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
                    <p>Voulez-vous vraiment supprimer ce candidat ?</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-danger" id="confirm-delete">Supprimer</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modalCandA" class="modal fade" role="dialog">
        <div class="modal-dialog modal-confirm">
            <div class="modal-content">
                <div class="modal-header flex-column">
                    <div class="icon-boxA">
                        <i class="bx bx-check"></i>
                    </div>
                    <h4 class="modal-title w-100">Êtes-vous sûr ?</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Voulez-vous vraiment accepter ce candidat ? Cela entraînera une acceptation définitif</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-success" id="confirm-accepter">Accepter</button>
                </div>
            </div>
        </div>
    </div>
    <div id="modalCandR" class="modal fade" role="dialog">
        <div class="modal-dialog modal-confirm">
            <div class="modal-content">
                <div class="modal-header flex-column">
                    <div class="icon-box">
                        <i class="bx bx-x material-icons"></i>
                    </div>
                    <h4 class="modal-title w-100">Êtes-vous sûr ?</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Voulez-vous vraiment refuser ce candidat ? Cela entraînera un refus définitif</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-danger" id="confirm-refuser">Refuser</button>
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
    <script type="text/javascript" src="/js/editCandidat.js"></script>
    </body>

    </html>
<?php } ?>