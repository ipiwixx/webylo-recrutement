<?php

require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/SMTP.php';
require_once 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * /model/UtilisateurManager.php
 *
 * Définition de la class UtilisateurManager
 * Class qui gère les interactions entre les utilisateurs de l'application
 * et les utilisateurs de la bdd
 *
 * @author A. Espinoza
 * @date 03/2023
 */

class UtilisateurManager
{

    private static ?\PDO $cnx = null;
    private static Utilisateur $unUtilisateur;
    private static array $lesUtilisateurs = array();

    /**
     * recupMdp
     * vérifie si l'email existe
     * puis envoie un code par email pour récupérer son mot de passe
     *
     * @return string
     */
    public static function recupMdp(): string
    {
        try {
            if (self::$cnx == null) {
                self::$cnx = DbManager::getConnexion();
            }

            $error = '';

            // Vérifie que tous les champs sont remplis
            if (isset($_POST['recup_submit']) && isset($_POST['recup_mail'])) {

                // Filtre les input de type poste pour enlever les caractères indésirables
                $recupMail = filter_input(INPUT_POST, 'recup_mail', FILTER_DEFAULT);

                // Vérifie que l'email est de la bonne forme
                if (filter_var($recupMail, FILTER_VALIDATE_EMAIL)) {

                    // Requête select qui récupère toutes les informations de l'utilisateur
                    $sql = 'SELECT id, nom, prenom, email FROM utilisateur WHERE email = :param_email;';
                    self::$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $stmt = self::$cnx->prepare($sql);
                    $stmt->bindParam(':param_email', $recupMail, PDO::PARAM_STR);
                    $stmt->execute();
                    $row = $stmt->rowCount();
                    $_SESSION['recup_mail'] = $recupMail;

                    // Si la requete renvoie un 0 alors l'utilisateur n'existe pas
                    if ($row == 1) {
                        $fetch = $stmt->fetch();
                        $nom = $fetch['nom'];
                        $prenom = $fetch['prenom'];

                        $recupCode = "";

                        // Créer un code aléatoire de 8 chiffre
                        for ($i = 0; $i < 8; $i++) {
                            $recupCode .= mt_rand(0, 9);
                        }

                        // Requête select qui récupère toutes les informations des candidats
                        $mailRecupExist = 'SELECT id FROM recuperation WHERE email = :email;';
                        $stmt = self::$cnx->prepare($mailRecupExist);
                        $stmt->bindParam(':email', $recupMail, PDO::PARAM_STR);
                        $stmt->execute();
                        $row = $stmt->rowCount();

                        // L'utilisateur à déja un code de recup donc on update
                        if ($row == 1) {

                            // Requête update qui modifie le code de l'utilisateur
                            $recupUpdate = 'UPDATE recuperation SET code = :code WHERE email = :email;';
                            $stmt = self::$cnx->prepare($recupUpdate);
                            $stmt->bindParam(':code', $recupCode, PDO::PARAM_INT);
                            $stmt->bindParam(':email', $recupMail, PDO::PARAM_STR);
                            $stmt->execute();

                            // L'utilisateur n'a pas de code de recup
                        } else {

                            // Requête insert qui insère un code à l'utilisateur
                            $recupInsert = 'INSERT INTO recuperation (email, code) VALUES (:email, :code);';
                            $stmt = self::$cnx->prepare($recupInsert);
                            $stmt->bindParam(':email', $recupMail, PDO::PARAM_STR);
                            $stmt->bindParam(':code', $recupCode, PDO::PARAM_INT);
                            $stmt->execute();
                        }

                        // Crée une instance de PHPMailer
                        $mail = new PHPMailer(true);

                        $mail->isSMTP(); // Active l'envoi via SMTP
                        $mail->Host = 'sandbox.smtp.mailtrap.io'; // Adresse du serveur SMTP
                        $mail->SMTPAuth = true; // Activation de l'authentification SMTP
                        $mail->Username = '14d4bc1fa326d5'; // Nom d'utilisateur SMTP
                        $mail->Password = '5aaad707afb027'; // Mot de passe SMTP
                        $mail->SMTPSecure = 'tls'; // Type de sécurité SMTP (tls ou ssl)
                        $mail->Port = 2525; // Port SMTP
                        $mail->CharSet = 'UTF-8';
                        $mail->isHTML(true);

                        // Paramètres de base
                        $mail->setFrom('contact@webylo.fr', 'Webylo');
                        $mail->addAddress($_SESSION['recup_mail'], $nom . ' ' . $prenom);

                        $mail->AddEmbeddedImage("img/logo.png", "logo", "logo.png");
                        $mail->AddEmbeddedImage("img/webylo.png", "webylo", "webylo.png");

                        // Envoie un email de récupération de mot de passe
                        $mail->Subject = 'Récupération de mot de passe Webylo';
                        $mail->Body = '<!DOCTYPE html>
                        <head>
                            <title>Récupération de mot de passe - recrutement.webylo.fr</title>
                            <meta charset="utf-8" />
                        </head>
                        <body>
                            <div style="padding: 0 10% 0 15%;">
                                <div style="justify-content: center;display: flex;text-align: center;">
                                    <img src="cid:logo" alt="webylo_logo" style="width: 60%;">
                                </div>
                                <div>
                                    <br />
                                    Bonjour <b>' . $prenom . ' ' . $nom . '</b>,<br /><br />
                                    Nous avons bien reçu votre demande de réinitialisation de votre mot de passe pour accéder à votre compte. Afin de procéder à cette réinitialisation, nous vous envoyons un code de vérification unique : <b>' . $recupCode . '.</b><br /><br />
                                    Veuillez ne pas partager ce code confidentiel avec qui que ce soit.<br /><br />
                                    Cordialement,<br /><br />
                                    La Team Webylo<br /><br /><br /><br />
                                </div>
                                <div style="text-align: center;">
                                    <hr>
                                    <font size="2">
                                        Ceci est un email automatique, merci de ne pas y répondre
                                    </font>
                                </div>
                                <div style="justify-content: center;display: flex;text-align: center;margin-top: 5%;">
                                    <img src="cid:webylo" alt="webylo_icon" style="width: 40%;">
                                </div>
                            </div>
                        </body>
                        </html>';

                        $mail->AltBody = 'Bonjour <b>' . $prenom . ' ' . $nom . '</b>,

                        Nous avons bien reçu votre demande de réinitialisation de votre mot de passe pour accéder à votre compte. Afin de procéder à cette réinitialisation, nous vous envoyons un code de vérification unique : <b>' . $recupCode . '.</b>

                        Veuillez ne pas partager ce code confidentiel avec qui que ce soit.

                        Cordialement,

                        La Team Webylo';

                        // Envoyer le message et vérifier si l'envoi a réussi
                        if ($mail->send()) {
                            $msg = 'Le message a été envoyé avec succès !';
                        } else {
                            $msg = 'Une erreur s\'est produite lors de l\'envoi du message : ' . $mail->ErrorInfo;
                        }

                        header('Location: ' . SERVER_URL . '/mot-de-passe-oublié-code/');
                    }
                    header('Location: ' . SERVER_URL . '/mot-de-passe-oublié-code/');
                }
            }

            // Vérifie que tous les champs sont remplis
            if (isset($_POST['verif_submit']) && isset($_POST['verif_code'])) {

                // Si le champ n'est pas vide
                if (!empty($_POST['verif_code'])) {

                    // Filtre les input de type poste pour enlever les caractères indésirables
                    $verifCode = filter_input(INPUT_POST, 'verif_code', FILTER_DEFAULT);

                    // Récupère l'adresse ip de l'utilisateur qui essaye de se connecter
                    $ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);

                    date_default_timezone_set('Europe/Paris');
                    $dateC = new DateTime();
                    $dateC = $dateC->format('Y-m-d H:i:s');

                    // Requête delete qui supprime les anciennes log de connexion
                    $sql = 'DELETE FROM logins';
                    $sql .= ' WHERE created_at < DATE_SUB(NOW(), INTERVAL 5 MINUTE);';
                    $stmt = self::$cnx->prepare($sql);
                    $stmt->execute();

                    // Requête select qui récupère le nombre de tentative de connexion d'une ip et d'un mail
                    $sql = 'SELECT COUNT(*) AS NbTentative FROM logins';
                    $sql .= ' WHERE ip = :ip and email = :email and created_at BETWEEN DATE_SUB(NOW(), INTERVAL 5 MINUTE) AND NOW();';
                    $stmt = self::$cnx->prepare($sql);
                    $stmt->bindParam(':ip', $ip, PDO::PARAM_STR);
                    $stmt->bindParam(':email', $_SESSION['recup_mail'], PDO::PARAM_STR);
                    $stmt->execute();
                    $row = $stmt->fetch();

                    if ($row['NbTentative'] < 3) {

                        // Requête select qui récupère toutes les informations de l'utilisateur
                        $verifReq = 'SELECT id, email, code FROM recuperation WHERE email = :email and code = :code;';
                        $stmt = self::$cnx->prepare($verifReq);
                        $stmt->bindParam(':email', $_SESSION['recup_mail'], PDO::PARAM_STR);
                        $stmt->bindParam(':code', $verifCode, PDO::PARAM_INT);
                        $stmt->execute();
                        $row = $stmt->rowCount();

                        // Si le code de recup est bon pour l'email
                        if ($row == 1) {

                            // Requête update pour modifié le champ confirme (le code est bon)
                            $updateReq = 'UPDATE recuperation SET confirme = 1 WHERE email = :email';
                            $stmt = self::$cnx->prepare($updateReq);
                            $stmt->bindParam(':email', $_SESSION['recup_mail'], PDO::PARAM_STR);
                            $stmt->execute();
                            header('Location: ' . SERVER_URL . '/changer-mot-de-passe/');
                        } else {
                            $error = '<div class="col-4 alert alert-danger">
                            <strong>Erreur</strong> Code invalide !
                            </div>';

                            // Requête insert qui insère une connexion
                            $sql = 'INSERT INTO logins (created_at, email, ip) VALUES';
                            $sql .= ' (:created_at, :email, :ip);';
                            $stmt = self::$cnx->prepare($sql);
                            $stmt->bindParam(':created_at', $dateC, PDO::PARAM_STR);
                            $stmt->bindParam(':email', $_SESSION['recup_mail'], PDO::PARAM_STR);
                            $stmt->bindParam(':ip', $ip, PDO::PARAM_STR);
                            $stmt->execute();
                        }
                    } else {
                        $error = '<div class="col-4 alert alert-danger">
                        <strong>Erreur</strong> Trop de tentatives. Réessayer dans 5 minutes !
                        </div>';
                    }
                } else {
                    $error = '<div class="col-4 alert alert-danger">
                    <strong>Erreur</strong> Veuillez entrer votre code de confirmation !
                    </div>';
                }
            }

            if (isset($_POST['change_submit'])) {

                // Vérifie que tous les champs sont remplis
                if (isset($_POST['change_mdp']) && isset($_POST['change_mdpc'])) {

                    // Requête select qui récupère le champ confirme de l'utilisateur
                    $verifConfirme = 'SELECT confirme FROM recuperation WHERE email = :email';
                    $stmt = self::$cnx->prepare($verifConfirme);
                    $stmt->bindParam(':email', $_SESSION['recup_mail'], PDO::PARAM_STR);
                    $stmt->execute();
                    $fetch = $stmt->fetch();
                    $verifConf = $fetch['confirme'];

                    // Si le code de recup est bon (confirme = 1)
                    if ($verifConf == 1) {

                        // Filtre les input de type poste pour enlever les caractères indésirables
                        $mdp = filter_input(INPUT_POST, 'change_mdp', FILTER_DEFAULT);
                        $mdpc = filter_input(INPUT_POST, 'change_mdpc', FILTER_DEFAULT);

                        // Si les champs sont pas vide
                        if (!empty($mdp) && !empty($mdpc)) {

                            // Vérifie que les 2 mot de passes correspondent
                            if ($mdp == $mdpc) {
                                $cost = ['cost' => 12];
                                // Hash le mot de passe
                                $mdp = password_hash($mdp, PASSWORD_BCRYPT, $cost);

                                // Requête update qui modifie le mot de passe de l'utilisateur
                                $insertMdp = 'UPDATE utilisateur SET mdp = :mdp WHERE email = :email';
                                $stmt = self::$cnx->prepare($insertMdp);
                                $stmt->bindParam(':mdp', $mdp, PDO::PARAM_STR);
                                $stmt->bindParam(':email', $_SESSION['recup_mail'], PDO::PARAM_STR);
                                $stmt->execute();

                                // Requête delete qui supprime la recuperation de l'utilisateur
                                $deleteReq = 'DELETE FROM recuperation WHERE email = :email';
                                $stmt = self::$cnx->prepare($deleteReq);
                                $stmt->bindParam(':email', $_SESSION['recup_mail'], PDO::PARAM_STR);
                                $stmt->execute();

                                header('Location: ' . SERVER_URL . '/connexion/');
                            } else {
                                $error = '<div class="col-4 alert alert-danger">
                                <strong>Erreur</strong> Vos mots de passes ne correspondent pas !
                                </div>';
                            }
                        } else {
                            $error = '<div class="col-4 alert alert-danger">
                            <strong>Erreur</strong> Veuillez remplir tous les champs !
                            </div>';
                        }
                    } else {
                        $error = '<div class="col-4 alert alert-danger">
                        <strong>Erreur</strong> Veuillez valider votre email grâce au code vérification !
                        </div>';
                    }
                } else {
                    $error = '<div class="col-4 alert alert-danger">
                    <strong>Erreur</strong> Veuillez remplir tous les champs !
                    </div>';
                }
            }

            return $error;
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    /**
     * testLaConnexion
     * teste la connexion de l'utilisateur admin
     *
     * @param string
     * @param string
     * @return string
     */
    public static function testLaConnexion($email, $mdp): string
    {
        try {
            if (self::$cnx == null) {
                self::$cnx = DbManager::getConnexion();
            }
            $mess = '';

            // Récupère l'adresse ip de l'utilisateur qui essaye de se connecter
            $ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);

            date_default_timezone_set('Europe/Paris');
            $dateC = new DateTime();
            $dateC = $dateC->format('Y-m-d H:i:s');

            // Requête delete qui supprime les anciennes log de connexion
            $sql = 'DELETE FROM logins';
            $sql .= ' WHERE created_at < DATE_SUB(NOW(), INTERVAL 5 MINUTE);';
            $stmt = self::$cnx->prepare($sql);
            $stmt->execute();

            // Requête select qui récupère le nombre de tentative de connexion d'une ip et d'un mail
            $sql = 'SELECT COUNT(*) AS NbTentative FROM logins';
            $sql .= ' WHERE ip = :ip and email = :email and created_at BETWEEN DATE_SUB(NOW(), INTERVAL 5 MINUTE) AND NOW();';
            $stmt = self::$cnx->prepare($sql);
            $stmt->bindParam(':ip', $ip, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch();

            if ($row['NbTentative'] < 3) {

                // Requête select qui récupère toutes les informations de l'utilisateur
                $sql = 'SELECT id, mdp, nom, prenom FROM utilisateur WHERE email = :param_email';
                $stmt = self::$cnx->prepare($sql);
                $stmt->bindParam(':param_email', $email, PDO::PARAM_STR);
                $stmt->execute();
                $row = $stmt->fetch();

                // L'utilisateur existe
                if ($row >= 1) {
                    $hash = $row['mdp'];
                    $id = $row['id'];
                    $nom = $row['nom'];
                    $prenom = $row['prenom'];

                    // Vérifie les 2 mots de passes
                    $resultAuth = password_verify($mdp, $hash);

                    // Si l'utilisateur a coché "se souvenir de moi"
                    if (isset($_POST['remember'])) {
                        $keyCookie = 'gK/9NcMJdNxJTtmp0SBa7w==xLCs.xunD9uNzief2gw9Qh.ZP7vuoCOCS3l';
                        $email = openssl_encrypt($email, "AES-128-ECB", $keyCookie);
                        $mdp = openssl_encrypt($mdp, "AES-128-ECB", $keyCookie);
                        setcookie('comail', $email, time() + 3600 * 24 * 100, '/', '', false, true);
                        setcookie('copassword', $mdp, time() + 3600 * 24 * 100, '/', '', false, true);
                    } else {
                        if (isset($_COOKIE['copassword']) && isset($_COOKIE['comail'])) {
                            setcookie('comail', '', -1, '/', '', false, true);
                            setcookie('copassword', '', -1, '/', '', false, true);
                        }
                    }

                    // Les 2 mots de passes correspondent
                    if ($resultAuth) {
                        $_SESSION['LOGGED_USER'] = $email;
                        $_SESSION['id'] = $id;
                        $_SESSION['nom'] = $nom;
                        $_SESSION['prenom'] = $prenom;
                        $_SESSION['user'] = UtilisateurManager::getUtilisateurById($id);

                        // Message de succès de connexion
                        $mess = '<div class="col-4 alert alert-success">
                        <strong>Succès</strong> Connexion réussie !
                        </div>';

                        header('Location: ' . SERVER_URL . '/candidat/');
                    } else {
                        // Message d'erreur de connexion
                        $mess = '<div class="col-4 alert alert-danger">
                        <strong>Erreur</strong> Identifiants incorrect !
                        </div>';

                        // Requête insert qui insère une connexion
                        $sql = 'INSERT INTO logins (created_at, email, ip) VALUES';
                        $sql .= ' (:created_at, :email, :ip);';
                        $stmt = self::$cnx->prepare($sql);
                        $stmt->bindParam(':created_at', $dateC, PDO::PARAM_STR);
                        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                        $stmt->bindParam(':ip', $ip, PDO::PARAM_STR);
                        $stmt->execute();
                    }
                } else {
                    // Message d'erreur de connexion
                    $mess = '<div class="col-4 alert alert-danger">
                    <strong>Erreur</strong> Identifiants incorrect !
                    </div>';

                    // Requête insert qui insère une connexion
                    $sql = 'INSERT INTO logins (created_at, email, ip) VALUES';
                    $sql .= ' (:created_at, :email, :ip);';
                    $stmt = self::$cnx->prepare($sql);
                    $stmt->bindParam(':created_at', $dateC, PDO::PARAM_STR);
                    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                    $stmt->bindParam(':ip', $ip, PDO::PARAM_STR);
                    $stmt->execute();
                }
            } else {
                // Message d'erreur de connexion
                $mess = '<div class="col-4 alert alert-danger">
                <strong>Erreur</strong> Trop de tentatives. Réessayer dans 5 minutes !
                </div>';
            }

            return $mess;
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    /**
     * deleteUtilisateur
     * supprime l'utilisateur avec l'id passé en paramètre
     *
     * @param int
     * @return void
     */
    public static function deleteUtilisateur(int $idUtilisateur): void
    {
        try {
            if (self::$cnx == null) {
                self::$cnx = DbManager::getConnexion();
            }

            // Requête delete qui supprime l'utilisateur
            $sql = 'DELETE FROM utilisateur';
            $sql .= ' WHERE id = :idU;';
            $stmt = self::$cnx->prepare($sql);
            $stmt->bindParam(':idU', $idUtilisateur, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    /**
     * addUtilisateur
     * ajoute l'utilisateur avec les valeurs saisies en paramètre
     *
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     * @return void
     */
    public static function addUtilisateur(string $nom, string $prenom, string $email, string $mdp, string $role): void
    {
        try {
            if (self::$cnx == null) {
                self::$cnx = DbManager::getConnexion();
            }

            // Hash le mot de passe avec Bcrypt, via un coût de 12
            $cost = ['cost' => 12];
            $mdp = password_hash($mdp, PASSWORD_BCRYPT, $cost);

            // Met en minuscule l'email
            $email = strtolower($email);

            // Met en majuscule la première lettre du prénom
            if (strpos($prenom, "-") !== false) {
                $prenoms = explode('-', $prenom);
                foreach ($prenoms as &$unPrenom) {
                    $unPrenom = ucfirst($unPrenom);
                }
                $prenom = implode('-', $prenoms);
            } else {
                $prenom = ucfirst($prenom);
            }

            // Met en majuscule le nom de famille
            $nom = strtoupper($nom);

            // Requête insert qui insère un nouvel utilisateur
            $sql = 'INSERT INTO `utilisateur` (`nom`, `prenom`, `email`, `mdp`, `roles`) VALUES';
            $sql .= ' (:nom, :prenom, :email, :mdp, :role);';
            $stmt = self::$cnx->prepare($sql);
            $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':mdp', $mdp, PDO::PARAM_STR);
            $stmt->bindParam(':role', $role, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    /**
     * editUtilisateur
     * modifie l'utilisateur avec les valeurs saisies en paramètre
     *
     * @param int
     * @param string
     * @param string
     * @param string
     * @param string
     * @return void
     */
    public static function editUtilisateur(int $idUtilisateur, string $nom, string $prenom, string $email, string $role): void
    {
        try {
            if (self::$cnx == null) {
                self::$cnx = DbManager::getConnexion();
            }

            // Met en minuscule l'email'
            $email = strtolower($email);

            // Met en majuscule la première lettre du prénom
            if (strpos($prenom, "-") !== false) {
                $prenoms = explode('-', $prenom);
                foreach ($prenoms as &$unPrenom) {
                    $unPrenom = ucfirst($unPrenom);
                }
                $prenom = implode('-', $prenoms);
            } else {
                $prenom = ucfirst($prenom);
            }

            // Met en majuscule le nom de famille
            $nom = strtoupper($nom);

            // Requête update qui modifie les valeurs de l'utilisateurs
            $sql = 'UPDATE utilisateur SET nom = :nom, prenom = :prenom, email = :email, roles = :roles';
            $sql .= ' WHERE id = :idU';
            $stmt = self::$cnx->prepare($sql);
            $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':roles', $role, PDO::PARAM_STR);
            $stmt->bindParam(':idU', $idUtilisateur, PDO::PARAM_INT);
            $stmt->execute();

            if ($idUtilisateur == $_SESSION['id']) {
                $_SESSION['LOGGED_USER'] = $email;
                $_SESSION['nom'] = $nom;
                $_SESSION['prenom'] = $prenom;
                $_SESSION['user'] = UtilisateurManager::getUtilisateurById($idUtilisateur);
            }
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    /**
     * editPassword
     * modifie le mot de passe de l'utilisateur avec les valeurs saisies en paramètre
     *
     * @param int
     * @param string
     * @param string
     * @return string
     */
    public static function editPassword(int $idUtilisateur, string $mdp, string $newMdp): string
    {
        try {
            if (self::$cnx == null) {
                self::$cnx = DbManager::getConnexion();
            }
            $mess = '';

            // Requête select qui récupère l'id et le mdp de l'utilisateur
            $sql = 'SELECT id, mdp FROM utilisateur WHERE id = :param_id';
            $stmt = self::$cnx->prepare($sql);
            $stmt->bindParam(':param_id', $idUtilisateur, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch();
            $hash = $row['mdp'];

            // Vérifie les 2 mots de passes
            $resultAuth = password_verify($mdp, $hash);

            // Les 2 mots de passes correspondent
            if ($resultAuth) {

                // On hash le mot de passe avec Bcrypt, via un coût de 12
                $cost = ['cost' => 12];
                $newMdp = password_hash($newMdp, PASSWORD_BCRYPT, $cost);

                // Requête update qui modifie le mot de passe de l'utilisateur
                $sql = "UPDATE utilisateur SET mdp = :mdp";
                $sql .= " WHERE id = :param_id;";
                $stmt = self::$cnx->prepare($sql);
                $stmt->bindParam(':mdp', $newMdp, PDO::PARAM_STR);
                $stmt->bindParam(':param_id', $idUtilisateur, PDO::PARAM_INT);
                // Exécution de la requête
                $stmt->execute();

                // Message de succès
                $mess = '<div class="col-4 alert alert-success">
                <strong>Succès</strong> Modification du mot de passe réussie !
                </div>';
            } else {

                // Message d'erreur
                $mess = '<div class="col-4 alert alert-danger">
                <strong>Erreur</strong> Mot de passe incorrect !
                </div>';
            }

            return $mess;
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    /**
     * getLesUtilisateurs
     * récupère dans la bbd tous les utilisateurs
     *
     * @return array
     */
    public static function getLesUtilisateurs(): array
    {
        try {
            if (self::$cnx == null) {
                self::$cnx = DbManager::getConnexion();
            }

            // Requête select qui récupère toutes les informations des utilisateurs
            $sql = 'SELECT id, nom, prenom, email, mdp, roles';
            $sql .= ' FROM utilisateur;';
            $stmt = self::$cnx->prepare($sql);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_OBJ);
            while ($row = $stmt->fetch()) {

                self::$unUtilisateur = new Utilisateur();
                self::$unUtilisateur->setId($row->id);
                self::$unUtilisateur->setNom($row->nom);
                self::$unUtilisateur->setPrenom($row->prenom);
                self::$unUtilisateur->setEmail($row->email);
                self::$unUtilisateur->setMdp($row->mdp);
                self::$unUtilisateur->setRole($row->roles);
                self::$lesUtilisateurs[] = self::$unUtilisateur;
            }
            return self::$lesUtilisateurs;
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    /**
     * getUtilisateurById
     * récupère dans la bbd l'utilisateur
     * avec l'id passé en paramètre
     *
     * @param int
     * @return Utilisateur
     */
    public static function getUtilisateurById(int $idUtilisateur): Utilisateur
    {
        try {
            if (self::$cnx == null) {
                self::$cnx = DbManager::getConnexion();
            }

            // Requête select qui récupère toutes les informations de l'utilisateur
            $sql = 'SELECT id, nom, prenom, email, mdp, roles';
            $sql .= ' FROM utilisateur';
            $sql .= ' WHERE id = :idUtilisateur;';
            $stmt = self::$cnx->prepare($sql);
            $stmt->bindParam(':idUtilisateur', $idUtilisateur, PDO::PARAM_INT);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_OBJ);
            $row = $stmt->fetch();

            self::$unUtilisateur = new Utilisateur();
            self::$unUtilisateur->setId($row->id);
            self::$unUtilisateur->setNom($row->nom);
            self::$unUtilisateur->setPrenom($row->prenom);
            self::$unUtilisateur->setEmail($row->email);
            self::$unUtilisateur->setMdp($row->mdp);
            self::$unUtilisateur->setRole($row->roles);

            return self::$unUtilisateur;
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    /**
     * existUtilisateur
     * vérifie si l'utilisateur existe
     *
     * @param int
     * @return bool
     */
    public static function existUtilisateur(int $idUtilisateur): bool
    {
        try {
            if (self::$cnx == null) {
                self::$cnx = DbManager::getConnexion();
            }
            $exist = false;

            // Requête select qui récupère toutes les informations de l'utilisateur
            $sql = ' SELECT id, nom, prenom, email FROM utilisateur';
            $sql .= ' WHERE id = :idU';
            $stmt = self::$cnx->prepare($sql);
            $stmt->bindParam(':idU', $idUtilisateur, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->rowCount();

            // L'utilisateur existe
            if ($row == 1) {
                $exist = true;
            }

            return $exist;
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }
}
