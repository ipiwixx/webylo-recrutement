<?php

/**
 * /view/addPoste.php
 *
 * Page pour l'ajout d'un poste
 * @author A. Espinoza
 * @date 03/2023
 */

if (!isset($_SESSION['user'])) {
    header('Location: /erreur/');
} else {

    $title = 'Ajout Poste | Webylo Recrutement';
    include_once 'header.php';

?>

    <form class="row g-3 needs-validation m-4 pt-5 pAddP" method="POST" action="/poste/ajouter/">
        <div class="text-center d-flex justify-content-center">
            <?= $mess ?>
        </div>
        <div class="row justify-content-center mt-3">
            <h1 class="mb-4 text-center">Ajouter Poste</h1>
            <div class="col-lg-5">
                <div class="mb-3">
                    <label for="libelle">Libelle</label>
                    <input type="text" name="libelle" class="form-control" id="libelle" placeholder="Libelle" maxlength="128" required>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="mb-3">
                    <label for="designation">Désignation</label>
                    <input type="text" name="designation" class="form-control" id="designation" placeholder="Désignation" maxlength="32" required>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="mb-3">
                    <label for="lienQuestion">Lien du questionnaire</label>
                    <input type="text" name="lienQuestion" class="form-control" id="lienQuestion" placeholder="Lien du questionnaire" maxlength="128">
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-2 ms-5">
                <button class="btnPink ms-5" name="addSubmit" type="submit">Ajouter</button>
            </div>
        </div>
        <div class="container">
            <a href="/poste/" class="mt-5 offset-3 btnBlue"><i class='bx bx-undo'></i>&nbsp;Dashboard</a>
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