<?php

/**
 * /view/code-mdp-oublie.php
 *
 * Page de confirmation de mot de passe oublié par l'envoie d'un code par mail
 *
 * @author A. Espinoza
 * @date 03/2023
 */

if (!isset($_SESSION['recup_mail'])) {
    header('Location: /erreur/');
} else {

    $title = 'Code mot de passe oublié | Webylo Recrutement';
    include_once 'header.php';

?>

    <div class="pMdpCo">
        <!-- Début jumbotron -->
        <div class="jumbotron">
            <h3 class="display-6 text-center">Un code de vérification vous a été envoyé par mail : <?= $_SESSION['recup_mail'] ?></h3>
            <p class="text-dark text-center"><strong>Regarder votre boîte mail</strong></p>
            <p class="text-center">Vous recevrez un code uniquement si vous avec un compte à cette adresse email</p>
        </div>
        <!-- Fin jumbotron -->

        <div class="d-flex justify-content-center text-center">
            <?= $error ?>
        </div>

        <form method="POST" action="/mot-de-passe-oublié-code/" class="row g-3 m-4 py-5">
            <div class="row mb-3 d-flex justify-content-center">
                <div class="col-lg-4">
                    <div class="mb-1">
                        <input type="text" name="verif_code" class="form-control" placeholder="Code" required pattern="^[0-9]{8}$" minlength="8" maxlength="8">
                    </div>
                    <p class="text-dark">Taper le code reçu sur votre email !</p>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <div class="col-lg-1">
                    <input class="btnPink ms-5" name="verif_submit" type="submit" value="Valider">
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