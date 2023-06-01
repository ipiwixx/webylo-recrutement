<?php

/**
 * /view/mdp-oublie.php
 *
 * Page de mot de passe oublié
 *
 * @author A. Espinoza
 * @date 03/2023
 */

if (isset($_SESSION['user'])) {
    header('Location: /erreur/');
} else {

    $title = 'Mot de passe oublié | Webylo Recrutement';
    include_once 'header.php';

?>

    <div class="pMdpO">
        <!-- Début jumbotron -->
        <div class="jumbotron">
            <h3 class="display-6 text-center">Mot de passe oublié ? </h3>
        </div>
        <!-- Fin jumbotron -->

        <div class="d-flex justify-content-center text-center">
            <?= $error ?>
        </div>

        <form method="POST" action="/mot-de-passe-oublié/" class="row g-3 m-4 py-5">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-4 col-8">
                    <div class="mb-1">
                        <input type="email" name="recup_mail" class="form-control" placeholder="nom@exemple.com" required pattern="^[A-Za-z0-9]+@{1}[A-Za-z]+\.{1}[A-Za-z]{2,}$">
                    </div>
                    <p class="text-dark">Renseigner votre adresse Email pour réinitialiser votre mot de passe !</p>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <div class="col-lg-1">
                    <input class="btnPink ms-3 mb-5" type="submit" value="Continuer" name="recup_submit">
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