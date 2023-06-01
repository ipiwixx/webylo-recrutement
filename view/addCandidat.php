<?php

/**
 * /view/addCandidat.php
 *
 * Page pour l'ajout d'un candidat
 * @author A. Espinoza
 * @date 03/2023
 */

if (!isset($_SESSION['user'])) {
    header('Location: /erreur/');
} else {

    $title = 'Ajout Candidat | Webylo Recrutement';
    include_once 'header.php';

?>


    <form class="row g-3 needs-validation m-4 pt-5" method="POST" action="/candidat/ajouter/">
        <?php if (!empty($mess)) { ?>
            <div class="text-center d-flex justify-content-center mt-5">
                <?= $mess ?>
            </div>
        <?php } ?>
        <div class="row justify-content-center pt-5 mt-5">
            <h1 class="mb-4 text-center">Ajouter Candidat</h1>
            <div class="col-lg-5">
                <div class="mb-3">
                    <label for="nom" class="text-dark">Nom</label>
                    <input type="text" name="nom" class="form-control" id="nom" pattern="^[A-Za-z]+$" placeholder="Nom" maxlength="64" required>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="mb-3">
                    <label for="prenom" class="text-dark">Prénom</label>
                    <input type="text" name="prenom" class="form-control" id="prenom" pattern="[a-zA-ZàâæçéèêëîïôœùûüÿÀÂÆÇnÉÈÊËÎÏÔŒÙÛÜŸ-]+" placeholder="Prénom" maxlength="64" required>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="mb-3">
                    <label for="dateN" class="text-dark">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Email" pattern="^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$" maxlength="128" required>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="mb-3">
                    <label for="tel" class="text-dark">Numéro de téléphone</label>
                    <input type="tel" name="tel" class="form-control" id="tel" placeholder="Numéro de téléphone" pattern="[0]{1}[0-9]{9}" minlength="10" maxlength="10" required>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="mb-3">
                    <label for="adresse" class="text-dark">Adresse</label>
                    <input type="text" name="adresse" class="form-control" id="adresse" placeholder="Adresse" minlength="12" maxlength="128" required>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="mb-3">
                    <label for="ville" class="text-dark">Ville</label>
                    <input type="text" name="ville" class="form-control" id="ville" placeholder="Ville" pattern="^[A-Za-z]+$" minlength="3" maxlength="64" required>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="mb-3">
                    <label for="cp" class="text-dark">Code postal</label>
                    <input type="text" name="cp" class="form-control" id="cp" placeholder="Code postal" pattern="[0-9]{5}" minlength="5" maxlength="5" required>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="mb-3">
                    <label for="dateN" class="text-dark">Date de naissance</label>
                    <input type="date" name="date" class="form-control" id="dateN" min="1945-01-01" max="<?= date('Y-m-d', strtotime('-16 year')) ?>" required>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <label for="poste" class="text-dark">Poste</label>
                <select class="form-select" name="poste" id="poste" required>
                    <option value="" selected>Selectionner un poste</option>
                    <?php foreach ($lesPostes as $unPoste) { ?>
                        <option value="<?= $unPoste->getId() ?>"><?= $unPoste->getLibelle() ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="row justify-content-center mt-3">
            <div class="col-lg-2">
                <button class="btnPink" name="addSubmit" type="submit">Ajouter</button>
            </div>
        </div>
        <div class="container">
            <a href="/candidat/" class="mt-5 offset-2 btnBlue"><i class='bx bx-undo'></i>&nbsp;Dashboard</a>
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