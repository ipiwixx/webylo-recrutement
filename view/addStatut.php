<?php

/**
 * /view/addStatut.php
 *
 * Page pour l'ajout d'un statut
 * @author A. Espinoza
 * @date 03/2023
 */

if (!isset($_SESSION['user'])) {
    header('Location: /erreur/');
} else {

    $title = 'Ajout Statut | Webylo Recrutement';
    include_once 'header.php';

?>

    <form class="row g-3 needs-validation m-4 pt-5 pAddS" method="POST" action="/statut/ajouter/">
        <div class="text-center">
            <?= $mess ?>
        </div>
        <div class="row justify-content-center">
            <h1 class="mb-4 text-center">Ajouter Statut</h1>
            <div class="col-lg-5">
                <div class="mb-3">
                    <label for="libelle">Libelle</label>
                    <input type="text" name="libelle" class="form-control" id="libelle" placeholder="Libelle" maxlength="64" required>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-2 ms-5">
                <button class="btnPink ms-5" name="addSubmit" type="submit">Ajouter</button>
            </div>
        </div>
        <div class="container">
            <a href="/statut/" class="mt-5 offset-2 btnBlue"><i class='bx bx-undo'></i>&nbsp;Dashboard</a>
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