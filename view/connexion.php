    <?php
    /**
     * /view/connexion.php
     *
     * Page de connexion
     *
     * @author A. Espinoza
     * @date 03/2023
     */

    $title = 'Connexion | Webylo Recrutement';
    include_once 'header.php';

    ?>

    <!-- Début formulaire connexion -->
    <form class="row g-3 needs-validation pt-5 m-4" method="POST" action="/connexion/">
        <div class="container mt-5">
            <div class="row pt-1">
                <div class="col-lg-6 mt-5 pt-5">
                    <div class="row d-flex justify-content-center">
                        <?= $mess ?>
                        <h1 class="mb-4 text-center">Connexion</h1>
                        <div class="col-lg-5 col-md-5 col-sm-6 col-8 my-2">
                            <div class="mb-3">
                                <label for="email" class="text-dark">Email</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="nom@exemple.com" value="<?php if (isset($_COOKIE['comail'])) {
                                                                                                                                            $decrypted_chaine = openssl_decrypt($_COOKIE['comail'], 'AES-128-ECB', 'gK/9NcMJdNxJTtmp0SBa7w==xLCs.xunD9uNzief2gw9Qh.ZP7vuoCOCS3l');
                                                                                                                                            echo $decrypted_chaine;
                                                                                                                                        } ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center">
                        <div class="col-lg-5 col-md-5 col-sm-6 col-8">
                            <div class="mb-3 lblCon">
                                <label for="mdp" class="text-dark">Mot de passe</label>
                                <input type="password" name="mdp" id="mdp" class="form-control" placeholder="Mot de passe" value="<?php if (isset($_COOKIE['copassword'])) {
                                                                                                                                        $decrypted_chaine = openssl_decrypt($_COOKIE['copassword'], 'AES-128-ECB', 'gK/9NcMJdNxJTtmp0SBa7w==xLCs.xunD9uNzief2gw9Qh.ZP7vuoCOCS3l');
                                                                                                                                        echo $decrypted_chaine;
                                                                                                                                    } ?>" required>
                                <div class="password-icon">
                                    <i class='bx bx-show'></i>
                                    <i class='bx bx-low-vision'></i>
                                </div>
                                <a class="links" href="/mot-de-passe-oublié/">Mot de passe oublié ?</a>
                            </div>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center">
                        <div class="col-lg-4 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" name="remember" type="checkbox" value="" id="remember">
                                <label class="form-check-label" for="remember">Se souvenir de moi</label>
                            </div>
                        </div>
                        <div class="text-start">
                            <div class="offset-3">
                                <p>Je veux postuler dans l'agence Webylo. Je <a href="https://recrutement.webylo.fr" class="links">postule</a>.</p>
                            </div>
                            <div class="d-flex justify-content-center">
                                <div class="col-lg-2">
                                    <button class="btnPink mb-3" name="loginSumbit" type="submit">se connecter</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 d-flex justify-content-center">
                    <img src="/img/webylo-acc.jpg" class="webylo-acc" alt="webylo-acc">
                </div>
            </div>
        </div>
    </form>
    <!-- Fin formulaire connexion -->

    <!-- Début footer -->
    <?php
    include_once 'footer.php';
    ?>
    <!-- Fin footer -->

    <!-- JS Libraries -->
    <script type="text/javascript" src="/js/connexion.js"></script>
    </body>

    </html>