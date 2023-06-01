<?php

/**
 * /view/change-mdp.php
 *
 * Page de mot de passe oublié
 *
 * @author A. Espinoza
 * @date 03/2023
 */

if (!isset($_SESSION['recup_mail'])) {
    header('Location: /erreur/');
} else {

    $title = 'Changer mot de passe | Webylo Recrutement';
    include_once 'header.php';

?>
    <div class="pMdpCh">
        <!-- Début jumbotron -->
        <div class="jumbotron">
            <h3 class="display-6 text-center">Veillez à mettre un mot de passe sécurisé</h3>
        </div>
        <!-- Fin jumbotron -->

        <div class="d-flex justify-content-center text-center">
            <?= $error ?>
        </div>

        <form method="POST" action="/changer-mot-de-passe/" class="row g-3 m-4 py-5">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-4 col-10">
                    <div class="mb-3">
                        <input type="password" name="change_mdp" class="form-control" placeholder="Mot de passe" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" minlength="8" TITLE="Le mot de passe doit contenir au moins 8 caractères composés d'au moins un chiffre et d'une lettre majuscule et minuscule.">
                    </div>
                    <div class="mb-1">
                        <input type="password" name="change_mdpc" class="form-control" placeholder="Confirmer le mot de passe" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" minlength="8">
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <div class="col-lg-1">
                    <input class="btnPink ms-3" type="submit" value="Continuer" name="change_submit">
                </div>
            </div>
        </form>
    </div>

    <!-- Début footer -->
    <?php
    include_once 'footer.php';
    ?>
    <!-- Fin footer -->
    </body>

    </html>
<?php } ?>