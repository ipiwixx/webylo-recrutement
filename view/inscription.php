<?php

/**
 * /view/inscription.php
 *
 * Page d'inscritpion du candidat
 *
 * @author A. Espinoza
 * @date 03/2023
 */

$title = 'Recrutement | Webylo Recrutement';
include_once 'header.php';

?>


<!-- Début formulaire inscription -->
<form method="POST" action="/" enctype="multipart/form-data" class="row g-3 needs-validation m-4 py-5 d-flex justify-content-center">
    <div class="d-flex justify-content-center text-center mt-5">
        <?= $mess ?>
    </div>
    <h1 class="mb-4 text-center">Recrutement Webylo</h1>

    <div class="col-lg-6 text-center">
        <p>Nous vous remercions de l'intérêt que vous portez pour notre agence Webylo. Nous vous prions de bien vouloir renseigner vos informations afin de pouvoir postuler.</p>
    </div>
    <div class="row justify-content-center mt-3">
        <div class="col-lg-2 mb-3">
            <label for="nom" class="text-dark">Nom</label>
            <input type="text" name="nom" class="form-control" id="nom" placeholder="Nom" pattern="^[A-Za-z]+$" minlength="2" maxlength="64" required>
        </div>
        <div class="col-lg-2 mb-3">
            <label for="prenom" class="text-dark">Prénom</label>
            <input type="text" name="prenom" class="form-control" id="prenom" placeholder="Prénom" pattern="[a-zA-ZàâæçéèêëîïôœùûüÿÀÂÆÇnÉÈÊËÎÏÔŒÙÛÜŸ-]+" minlength="2" maxlength="64" required>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-4">
            <div class="mb-3">
                <label for="email" class="text-dark">Email</label>
                <input type="email" name="email" class="form-control" id="email" placeholder="nom@exemple.com" pattern="^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$" maxlength="128" required>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-4">
            <div class="mb-1">
                <label for="tel" class="text-dark">Numéro de téléphone</label>
                <input type="tel" name="tel" class="form-control" id="tel" placeholder="Numéro de téléphone" pattern="[0]{1}[0-9]{9}" minlength="10" maxlength="10" required>
            </div>
            <p class="text-muted">Assurez-vous de saisir un numéro de téléphone auquel vous pourrez toujours accéder.</p>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-4 mb-3">
            <label for="dateN" class="text-dark">Date de naissance</label>
            <input type="date" name="date" class="form-control" id="dateN" min="1945-01-01" max="<?= date('Y-m-d', strtotime('-16 year')) ?>" required>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-4 mb-3">
            <label for="adresse" class="text-dark">Adresse</label>
            <input type="text" name="adresse" class="form-control" id="adresse" placeholder="Adresse" minlength="12" maxlength="128" required>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-2">
            <label for="ville" class="text-dark">Ville</label>
            <input type="text" name="ville" class="form-control" id="ville" placeholder="Ville" pattern="^[A-Za-z]+$" minlength="3" maxlength="64" required>
        </div>
        <div class="col-lg-2 mb-3">
            <label for="cp" class="text-dark">Code postal</label>
            <input type="text" name="cp" class="form-control" id="cp" placeholder="Code Postale" pattern="[0-9]{5}" minlength="5" maxlength="5" required>
        </div>
    </div>
    <?php if (!empty($lesPostes)) { ?>
        <div class="row justify-content-center">
            <div class="col-lg-4 mb-3">
                <label for="poste" class="text-dark">Poste</label>
                <select class="form-select" name="poste" id="poste" required>
                    <option value="" selected>Selectionner un poste</option>
                    <?php foreach ($lesPostes as $unPoste) { ?>
                        <option value="<?= $unPoste->getId() ?>"><?= $unPoste->getLibelle() ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
    <?php } ?>
    <div class="row justify-content-center">
        <div class="col-lg-4 mb-3">
            <label for="formFile">Déposer votre CV (format pdf)</label>
            <input class="form-control" name="cv" type="file" id="formFile" accept=".pdf" required>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-4 mb-3">
            <label for="formFileMultiple">Fichiers complémentaires (lettre de motivation, graphisme, image ...) [facultatif]</label>
            <input class="form-control" name="fichiersComplementaires[]" type="file" accept=".png, .jpeg, .jpg, .pdf, .doc, .docx" id="formFileMultiple" multiple>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-3">
            <div class="form-check">
                <input class="form-check-input is-invalid" name="valid" type="checkbox" id="invalidCheck3" aria-describedby="invalidCheck3Feedback" required>
                <label class="form-check-label text-muted" for="invalidCheck3">J'accepte les <a href="/politique-de-confidentialité/" class="links">termes et conditions</a></label>
                <div id="invalidCheck3Feedback" class="text-muted">
                    Vous devez accepter avant de soumettre le formulaire.
                </div>
            </div>
        </div>
    </div>
    <div class="col-2 text-center">
        <button class="btnPink" name="inscriptionSubmit" type="submit">Postuler</button>
    </div>
</form>
<!-- Fin formulaire inscription -->

<!-- Début footer -->
<?php
include_once 'footer.php';
?>
<!-- Fin footer -->
</body>

</html>