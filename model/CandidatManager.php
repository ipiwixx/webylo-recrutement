<?php

require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/SMTP.php';
require_once 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * /model/CandidatManager.php
 *
 * Définition de la class CandidatManager
 * Class qui gère les interactions entre les candidats de l'application
 * et les candidats de la bdd
 *
 * @author A. Espinoza
 * @date 03/2023
 */

class CandidatManager
{

    private static ?\PDO $cnx = null;
    private static Candidat $unCandidat;
    private static array $lesCandidats = array();

    /**
     * getLesCandidatsByStatut
     * récupère dans la bbd tous les candidats
     * avec l'id du statut passé en paramètre
     *
     * @param int
     * @return array
     */
    public static function getLesCandidatsByStatut(int $idStatut): array
    {
        try {
            if (self::$cnx == null) {
                self::$cnx = DbManager::getConnexion();
            }

            // Requête select qui récupère les candidats pour un statut
            $sql = 'SELECT C.id, nom, prenom, email, numTel, cp, ville, adresse, dateNaissance, dateInscription, note, cv, fichiersComplementaires, designation, S.libelle';
            $sql .= ' FROM candidat C';
            $sql .= ' JOIN postuler PO on PO.idCandidat = C.id';
            $sql .= ' JOIN poste P on P.id = PO.idPoste';
            $sql .= ' JOIN statut S on S.id = C.idStatut';
            $sql .= ' WHERE S.id = :id_statut';

            $stmt = self::$cnx->prepare($sql);
            $stmt->bindParam(':id_statut', $idStatut, PDO::PARAM_INT);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_OBJ);
            while ($row = $stmt->fetch()) {

                self::$unCandidat = new Candidat();
                self::$unCandidat->setId($row->id);
                self::$unCandidat->setNom($row->nom);
                self::$unCandidat->setPrenom($row->prenom);
                self::$unCandidat->setEmail($row->email);
                self::$unCandidat->setNumTel($row->numTel);
                self::$unCandidat->setCp($row->cp);
                self::$unCandidat->setVille($row->ville);
                self::$unCandidat->setAdresse($row->adresse);
                $laDateNaissance = new DateTime($row->dateNaissance);
                self::$unCandidat->setDateNaissance($laDateNaissance);
                $laDateInscription = new DateTime($row->dateInscription);
                self::$unCandidat->setDateInscription($laDateInscription);
                self::$unCandidat->setNote($row->note);
                self::$unCandidat->setCv($row->cv);
                self::$unCandidat->setFichiersComplementaires($row->fichiersComplementaires);
                self::$unCandidat->setDesiPoste($row->designation);
                self::$unCandidat->setLibStatut($row->libelle);
                self::$lesCandidats[] = self::$unCandidat;
            }
            return self::$lesCandidats;
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    /**
     * getUnCandidatById
     * récupère dans la bbd le candidat
     * avec l'id passé en paramètre
     *
     * @param int
     * @return Candidat
     */
    public static function getUnCandidatById(int $idCandidat): Candidat
    {
        try {
            if (self::$cnx == null) {
                self::$cnx = DbManager::getConnexion();
            }

            // Requête select qui récupère les informations d'un candidat
            $sql = 'SELECT C.id, nom, prenom, email, numTel, cp, ville, adresse, dateNaissance, dateInscription, note, cv, fichiersComplementaires, designation, S.libelle, idPoste, idStatut';
            $sql .= ' FROM candidat C';
            $sql .= ' JOIN postuler PO on PO.idCandidat = C.id';
            $sql .= ' JOIN poste P on P.id = PO.idPoste';
            $sql .= ' JOIN statut S on S.id = C.idStatut';
            $sql .= ' WHERE C.id = :id_candidat;';
            $stmt = self::$cnx->prepare($sql);
            $stmt->bindParam(':id_candidat', $idCandidat, PDO::PARAM_INT);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_OBJ);
            $row = $stmt->fetch();

            self::$unCandidat = new Candidat();
            self::$unCandidat->setId($row->id);
            self::$unCandidat->setNom($row->nom);
            self::$unCandidat->setPrenom($row->prenom);
            self::$unCandidat->setEmail($row->email);
            self::$unCandidat->setNumTel($row->numTel);
            self::$unCandidat->setCp($row->cp);
            self::$unCandidat->setVille($row->ville);
            self::$unCandidat->setAdresse($row->adresse);
            $laDateNaissance = new DateTime($row->dateNaissance);
            self::$unCandidat->setDateNaissance($laDateNaissance);
            $laDateInscription = new DateTime($row->dateInscription);
            self::$unCandidat->setDateInscription($laDateInscription);
            self::$unCandidat->setNote($row->note);
            self::$unCandidat->setCv($row->cv);
            self::$unCandidat->setFichiersComplementaires($row->fichiersComplementaires);
            self::$unCandidat->setDesiPoste($row->designation);
            self::$unCandidat->setLibStatut($row->libelle);
            self::$unCandidat->setIdPoste($row->idPoste);
            self::$unCandidat->setIdStatut($row->idStatut);

            return self::$unCandidat;
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    /**
     * accepteCandidat
     * modifie le statut du candidat en accepté
     * avec l'id du candidat passé en paramètre
     *
     * @param int
     * @return void
     */
    public static function accepteCandidat(int $idCandidat): void
    {
        try {
            if (self::$cnx == null) {
                self::$cnx = DbManager::getConnexion();
            }

            // Requête update qui accepte un candidat
            $sql = 'UPDATE candidat SET idStatut = 2';
            $sql .= ' WHERE id = :idC;';
            $stmt = self::$cnx->prepare($sql);
            $stmt->bindParam(':idC', $idCandidat, PDO::PARAM_INT);
            $stmt->execute();

            // Requête select qui récupère l'email, le libelle du poste et le lien du questionnaire du poste du candidat accepté
            $sql = 'SELECT email, nom, prenom, libelle, lienQuestion FROM candidat C';
            $sql .= ' JOIN postuler PO on PO.idCandidat = C.id';
            $sql .= ' JOIN poste P on P.id = PO.idPoste';
            $sql .= ' WHERE idCandidat = :idC';
            $stmt = self::$cnx->prepare($sql);
            $stmt->bindParam(':idC', $idCandidat, PDO::PARAM_INT);
            $stmt->execute();

            $row = $stmt->fetch();
            $email = $row['email'];
            $lienQuestion = $row['lienQuestion'];
            $libelleP = $row['libelle'];
            $nom = $row['nom'];
            $prenom = $row['prenom'];

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
            $mail->addAddress($email, $nom . ' ' . $prenom);

            $mail->AddEmbeddedImage("img/logo.png", "logo", "logo.png");
            $mail->AddEmbeddedImage("img/webylo.png", "webylo", "webylo.png");

            // Envoie un email de refus
            $mail->Subject = 'Votre candidature Webylo';
            $mail->Body = '<!DOCTYPE html>
            <head>
                <title>Acceptation candidature - recrutement.webylo.fr</title>
                <meta charset="utf-8" />
            </head>
            <body>
                <div style="padding: 0 10% 0 15%;">
                    <div style="justify-content: center;display: flex;text-align: center;">
                        <img src="cid:logo" alt="webylo_logo" style="width: 60%;">
                    </div>
                    <div>
                        <br />
                        Bonjour,</br></br>
                        Nous sommes ravis de vous informer que votre candidature pour le poste de ' . $libelleP . ' a été retenue pour la prochaine étape du processus de recrutement. Votre CV a su retenir toute notre attention.<br /><br />
                        Nous reviendrons vers vous prochainement par téléphone afin de convenir à une date d\'entretien.<br />
                        Au cours de cet entretien, nous souhaiterions en apprendre davantage sur votre expérience professionnelle, vos compétences et aborder les missions que vous serez amenées à faire dans notre agence. Nous serons également heureux de répondre à toutes les questions que vous pourriez avoir concernant le poste de Spécialiste en référencement et sur notre agence Webylo à l\'adresse email suivante : contact@webylo.fr ou par téléphone au 07 71 60 88 13.<br />
                        En vue de préparer l\'entretien, veuillez prendre le temps de répondre à ce questionnaire concernant le poste en <a href="' . $lienQuestion . '" style="color: rgb(208, 21, 97);">cliquant ici</a>.<br /><br />
                        Cordialement,<br /><br />
                        La Team Webylo<br /><br /></br></br>
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

            $mail->AltBody = 'Bonjour,

            Nous sommes ravis de vous informer que votre candidature pour le poste de ' . $libelleP . ' a été retenue pour la prochaine étape du processus de recrutement. Votre CV a su retenir toute notre attention.

            Nous reviendrons vers vous prochainement par téléphone afin de convenir à une date d\'entretien.

            Au cours de cet entretien, nous souhaiterions en apprendre davantage sur votre expérience professionnelle, vos compétences et aborder les missions que vous serez amenées à faire dans notre agence. Nous serons également heureux de répondre à toutes les questions que vous pourriez avoir concernant le poste de Spécialiste en référencement et sur notre agence Webylo à l\'adresse email suivante : contact@webylo.fr ou par téléphone au 07 71 60 88 13.
            
            En vue de préparer l\'entretien, veuillez prendre le temps de répondre à ce questionnaire concernant le poste en <a href="' . $lienQuestion . '" style="color: rgb(208, 21, 97);">cliquant ici</a>.

            Cordialement,

            La Team Webylo';

            // Envoyer le message et vérifier si l'envoi a réussi
            if ($mail->send()) {
                $msg = 'Le message a été envoyé avec succès !';
            } else {
                $msg = 'Une erreur s\'est produite lors de l\'envoi du message : ' . $mail->ErrorInfo;
            }
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    /**
     * refusCandidat
     * modifie le statut du candidat en refusé
     * avec l'id du candidat passé en paramètre
     *
     * @param int
     * @return void
     */
    public static function refusCandidat(int $idCandidat): void
    {
        try {
            if (self::$cnx == null) {
                self::$cnx = DbManager::getConnexion();
            }

            // Requête update qui refuse un candidat
            $sql = 'UPDATE candidat SET idStatut = 3';
            $sql .= ' WHERE id = :idC;';
            $stmt = self::$cnx->prepare($sql);
            $stmt->bindParam(':idC', $idCandidat, PDO::PARAM_INT);
            $stmt->execute();

            // Requête select qui récupère l'email et le libelle du poste du candidat refusé
            $sql = 'SELECT email, nom, prenom, libelle FROM candidat C';
            $sql .= ' JOIN postuler PO on PO.idCandidat = C.id';
            $sql .= ' JOIN poste P on P.id = PO.idPoste';
            $sql .= ' WHERE idCandidat = :idC';
            $stmt = self::$cnx->prepare($sql);
            $stmt->bindParam(':idC', $idCandidat, PDO::PARAM_INT);
            $stmt->execute();

            $row = $stmt->fetch();
            $email = $row['email'];
            $libelleP = $row['libelle'];
            $nom = $row['nom'];
            $prenom = $row['prenom'];

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
            $mail->addAddress($email, $nom . ' ' . $prenom);

            $mail->AddEmbeddedImage("img/logo.png", "logo", "logo.png");
            $mail->AddEmbeddedImage("img/webylo.png", "webylo", "webylo.png");

            // Envoie un email de refus
            $mail->Subject = 'Votre candidature Webylo';
            $mail->Body = '<!DOCTYPE html>
            <head>
                <title>Refus candidature - recrutement.webylo.fr</title>
                <meta charset="utf-8" />
            </head>
            <body>
                <div style="padding: 0 10% 0 15%;">
                    <div style="justify-content: center;display: flex;text-align: center;">
                        <img src="cid:logo" alt="webylo_logo" style="width: 60%;">
                    </div>
                    <div>
                        <br />
                        Bonjour,</br></br>
                        Nous tenons à vous remercier pour l\'intérêt que vous avez manifesté pour le poste de ' . $libelleP . ' au sein de notre agence Webylo. Nous avons étudié votre candidature avec attention et nous vous informons que nous ne sommes malheureusement pas en mesure de vous retenir pour la suite du processus de recrutement.<br /><br />
                        Soyez assuré(e) que nous avons apprécié vos compétences et vos qualifications, mais nous avons décidé de poursuivre avec d\'autres candidats qui correspondent plus étroitement à notre profil de recherche.<br /><br />
                        Nous tenons à vous remercier encore une fois pour l\'intérêt que vous avez porté à notre entreprise, et nous vous souhaitons une excellente continuation dans votre parcours professionnel.<br /><br />
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

            $mail->AltBody = 'Bonjour,

            Nous tenons à vous remercier pour l\'intérêt que vous avez manifesté pour le poste de ' . $libelleP . ' au sein de notre agence Webylo. Nous avons étudié votre candidature avec attention et nous vous informons que nous ne sommes malheureusement pas en mesure de vous retenir pour la suite du processus de recrutement.

            Soyez assuré(e) que nous avons apprécié vos compétences et vos qualifications, mais nous avons décidé de poursuivre avec d\'autres candidats qui correspondent plus étroitement à notre profil de recherche.

            Nous tenons à vous remercier encore une fois pour l\'intérêt que vous avez porté à notre entreprise, et nous vous souhaitons une excellente continuation dans votre parcours professionnel.

            Cordialement,

            La Team Webylo';

            // Envoyer le message et vérifier si l'envoi a réussi
            if ($mail->send()) {
                $msg = 'Le message a été envoyé avec succès !';
            } else {
                $msg = 'Une erreur s\'est produite lors de l\'envoi du message : ' . $mail->ErrorInfo;
            }
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    /**
     * inscription
     * teste l'inscription d'un candidat
     *
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     * @param int
     * @param int
     * @param int
     * @param int
     * @return string
     */
    public static function inscription(string $nom, string $prenom, string $email, string $tel, string $dateN, string $adresse, string $ville, int $cp, int $note, int $poste, int $maxsizes): string
    {
        try {
            if (self::$cnx == null) {
                self::$cnx = DbManager::getConnexion();
            }
            self::$cnx->beginTransaction();

            $mess = '';

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

            // Met en majuscule la première lettre de la ville
            $ville = ucfirst($ville);

            // Récupère l'extension du cv
            $extensionCVUpload = strtolower(substr(strrchr($_FILES['cv']['name'], '.'), 1));

            $extensionsFCValides = array('pdf', 'png', 'jpg', 'jpeg', 'doc', 'docx');

            // On vérifie si le candidat s'est déjà inscrit au poste sélectionné
            $sql = 'SELECT C.id, nom, prenom, email FROM candidat C JOIN postuler PO ON PO.idCandidat = C.id WHERE email = :param_email and idPoste = :param_idP';
            $stmt = self::$cnx->prepare($sql);
            $stmt->bindParam(':param_email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':param_idP', $poste, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->rowCount();

            // Si la requête renvoie un 0 alors le candidat n'a pas postuler pour le poste sélectionné
            if ($row == 0) {

                // Requête select qui récupère l'id du dernier candidat
                $sql = 'SELECT MAX(id) AS idC FROM candidat';
                $stmt = self::$cnx->prepare($sql);
                $stmt->execute();

                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                $row = $stmt->fetch();
                $idCandidat = $row['idC'];
                $idCandidat += 1;

                // Créer le lien vers le cv du candidat
                $cv = "cv/{$idCandidat}.{$extensionCVUpload}";

                // Clé de chiffrement (doit avoir 16, 24 ou 32 caractères)
                $key = 'dj9JXT9SSThs4ZgSQrPqgCnTGjB3w2J25bftnraeMp4Sf268w7PsTmBBeGqrmNGn';

                // Initialisation du vecteur d'initialisation (IV)
                $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));

                // Chiffrement du texte
                $texteChiffre = openssl_encrypt($idCandidat, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);

                // Concaténation de l'IV et du texte chiffré
                $idCandCrypt = base64_encode($iv . $texteChiffre);
                $crypte = str_replace(['+', '/', '='], ['5', '8', 'D'], $idCandCrypt);

                // Lien du cv crypté
                $cv = "cv/$crypte.$extensionCVUpload";

                // Déplace le cv de temp vers le dossier "cv"
                move_uploaded_file($_FILES['cv']['tmp_name'], $cv);

                // Récupère le nombre de fichiers complémentaires
                $nbFichiersComp = count($_FILES['fichiersComplementaires']['name']);
                $jsonString = '';
                if ($nbFichiersComp > 0) {
                    $jsonString = '{"liens": [';
                }

                for ($i = 0; $i < $nbFichiersComp; $i++) {
                    // Récupère les paramètres du fichier
                    $name = $_FILES['fichiersComplementaires']['name'][$i];
                    $type = $_FILES['fichiersComplementaires']['type'][$i];
                    $tmpName = $_FILES['fichiersComplementaires']['tmp_name'][$i];
                    $error = $_FILES['fichiersComplementaires']['error'][$i];
                    $size = $_FILES['fichiersComplementaires']['size'][$i];

                    // Récupère les extensions des fichiers complémentaires
                    $extensionFCcUpload = strtolower(substr(strrchr($name, '.'), 1));

                    // Clé de chiffrement (doit avoir 16, 24 ou 32 caractères)
                    $keyFc = 'rtcgjUs9NWhvJ3qp5UM76ZUHYGjJMJctQn8KD9RqKd87mvfKf9a6tG8xwJBdZ3Hy' . $i;

                    if ($error == 0) { // Vérifie si il y a une erreur de transfert des fichiers complémentaires

                        if (in_array($extensionFCcUpload, $extensionsFCValides)) { // Vérifie si l'extension est correcte

                            if ($size < $maxsizes) { // Vérifie que les fichiers complémentaires soient bien inférieur à la taille maximum

                                // Initialisation du vecteur d'initialisation (IV)
                                $ivFc = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));

                                // Chiffrement du texte
                                $texteChiffreFc = openssl_encrypt($i, 'AES-128-ECB', $keyFc);

                                // Concaténation de l'IV et du texte chiffré
                                $fichC = base64_encode($ivFc . $texteChiffreFc);
                                $crypte = str_replace(['+', '/', '='], ['5', '8', 'D'], $fichC);

                                // Créer le lien ver le fichier
                                $lien = "fichiersComp/$crypte.$extensionFCcUpload";

                                // Déplace fichier de temp vers le dossier "fichiersComp"
                                move_uploaded_file($tmpName, $lien);

                                if ($i == $nbFichiersComp - 1) {
                                    $jsonString .= '"' . $lien . '"';
                                } else {
                                    $jsonString .= '"' . $lien . '", ';
                                }
                            }
                        }
                    }
                }
                $jsonString .=  ']}';

                // Récupère la date d'aujourd'hui
                date_default_timezone_set('Europe/Paris');
                $dateI = new DateTime();
                $dateI = $dateI->format('Y-m-d');
                $idStatut = 1;

                // Requête insert qui insère un nouveau candidat
                $sql = "INSERT INTO `candidat` (`nom`, `prenom`, `email`, `numTel`, `cp`, `ville`, `adresse`, `dateNaissance`, `dateInscription`, `note`, `cv`, `fichiersComplementaires`, `idStatut`) VALUES
                (:nom, :prenom, :email, :tel, :cp, :ville, :adresse, :dateN, :dateI, :note, :cv, :fC, :idStatut);";
                $stmt = self::$cnx->prepare($sql);
                $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
                $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->bindParam(':tel', $tel, PDO::PARAM_INT);
                $stmt->bindParam(':cp', $cp, PDO::PARAM_INT);
                $stmt->bindParam(':ville', $ville, PDO::PARAM_STR);
                $stmt->bindParam(':adresse', $adresse, PDO::PARAM_STR);
                $stmt->bindParam(':dateN', $dateN, PDO::PARAM_STR);
                $stmt->bindParam(':dateI', $dateI, PDO::PARAM_STR);
                $stmt->bindParam(':note', $note, PDO::PARAM_INT);
                $stmt->bindParam(':cv', $cv, PDO::PARAM_STR);
                $stmt->bindParam(':fC', $jsonString, PDO::PARAM_STR);
                $stmt->bindParam(':idStatut', $idStatut, PDO::PARAM_INT);

                // Exécution de la requête
                $stmt->execute();

                // Requête select qui récupère l'id du candidat créé
                $sql = 'SELECT MAX(id) AS idC FROM candidat';
                $stmt = self::$cnx->prepare($sql);
                $stmt->execute();

                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                $row = $stmt->fetch();
                $idCandidat = $row['idC'];

                // Requête insert qui insère une candidature pour un poste
                $sql = "INSERT INTO `postuler` (`idCandidat`, `idPoste`) VALUES
                (:idCandidat, :idPoste);";
                $stmt = self::$cnx->prepare($sql);
                $stmt->bindParam(':idCandidat', $idCandidat, PDO::PARAM_INT);
                $stmt->bindParam(':idPoste', $poste, PDO::PARAM_INT);

                // Exécution de la requête
                $stmt->execute();

                // On affiche le message de succès
                $mess = '<div class="col-4 alert alert-success">
                <strong>Succès</strong> Votre candidature a bien été envoyé !
                </div>';

                // Requête select qui récupère l'email et le libelle du poste du candidat
                $sql = 'SELECT email, nom, prenom, libelle FROM candidat C';
                $sql .= ' JOIN postuler PO on PO.idCandidat = C.id';
                $sql .= ' JOIN poste P on P.id = PO.idPoste';
                $sql .= ' WHERE idCandidat = :idC';
                $stmt = self::$cnx->prepare($sql);
                $stmt->bindParam(':idC', $idCandidat, PDO::PARAM_INT);
                $stmt->execute();

                $row = $stmt->fetch();
                $email = $row['email'];
                $libelleP = $row['libelle'];
                $nom = $row['nom'];
                $prenom = $row['prenom'];

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
                $mail->addAddress($email, $nom . ' ' . $prenom);

                $mail->AddEmbeddedImage("img/logo.png", "logo", "logo.png");
                $mail->AddEmbeddedImage("img/webylo.png", "webylo", "webylo.png");

                // Envoie un email de confirmation de la candidature
                $mail->Subject = 'Votre candidature Webylo';
                $mail->Body = '<!DOCTYPE html>
                <head>
                    <title>Votre candidature - recrutement.webylo.fr</title>
                    <meta charset="utf-8" />
                </head>
                <body>
                    <div style="padding: 0 10% 0 15%;">
                        <div style="justify-content: center;display: flex;text-align: center;">
                            <img src="cid:logo" alt="webylo_logo" style="width: 60%;">
                        </div>
                        <div>
                            <br />
                            Bonjour,<br/><br/>
                            Nous tenons à vous remercier pour l\'intérêt que vous portez à notre agence Webylo et d\'avoir postulé au poste de ' . $libelleP . '<br /><br />
                            Nous vous confirmons que nous avons bien reçu votre candidature et nous l\'étudierons avec attention. Nous sommes actuellement en train d\'examiner l\'ensemble des candidatures reçues et nous prendrons contact avec vous dans les plus brefs délais afin de vous informer de la suite donnée à votre candidature.<br /><br />
                            Si vous avez des questions ou des préoccupations, n\'hésitez pas à nous contacter à l\'adresse e-mail suivante : contact@webylo.fr ou par téléphone au 07 71 60 88 13.<br /><br />
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

                $mail->AltBody = 'Bonjour,

                Nous tenons à vous remercier pour l\'intérêt que vous portez à notre agence Webylo et d\'avoir postulé au poste de ' . $libelleP . '
                
                Nous vous confirmons que nous avons bien reçu votre candidature et nous l\'étudierons avec attention. Nous sommes actuellement en train d\'examiner l\'ensemble des candidatures reçues et nous prendrons contact avec vous dans les plus brefs délais afin de vous informer de la suite donnée à votre candidature.

                Si vous avez des questions ou des préoccupations, n\'hésitez pas à nous contacter à l\'adresse e-mail suivante : contact@webylo.fr ou par téléphone au 07 71 60 88 13.

                Cordialement,

                La Team Webylo';

                // Envoyer le message et vérifier si l'envoi a réussi
                if ($mail->send()) {
                    $msg = 'Le message a été envoyé avec succès !';
                } else {
                    $msg = 'Une erreur s\'est produite lors de l\'envoi du message : ' . $mail->ErrorInfo;
                }
            } else {
                // Message d'erreur, l'utilisateur existe déjà
                $mess = '<div class="col-4 alert alert-danger">
                <strong>Erreur</strong> Vous avez déjà postulé pour ce poste
                </div>';
            }

            self::$cnx->commit();

            return $mess;
        } catch (PDOException $e) {

            self::$cnx->rollBack();

            die('Erreur : ' . $e->getMessage());
        }
    }

    /**
     * deleteCandidat
     * supprime le candidat avec l'id passé en paramètre
     *
     * @param int
     * @return void
     */
    public static function deleteCandidat(int $idCandidat): void
    {
        try {
            if (self::$cnx == null) {
                self::$cnx = DbManager::getConnexion();
            }

            // Requête select qui récupère le CV et les fichiers complémentaires du candidat
            $sql = 'SELECT id, nom, prenom, cv, fichiersComplementaires';
            $sql .= ' FROM candidat';
            $sql .= ' WHERE id = :idC';
            $stmt = self::$cnx->prepare($sql);
            $stmt->bindParam(':idC', $idCandidat, PDO::PARAM_INT);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $row = $stmt->fetch();

            // Renome le cv en lui mettant une valeure crypté
            if (file_exists('cv/' . $row['nom'] . '_' . $row['prenom'] . '_' . $row['id'] . '.pdf')) {
                rename('cv/' . $row['nom'] . '_' . $row['prenom'] . '_' . $row['id'] . '.pdf', $row['cv']);
                // Suppression du cv
                unlink($row['cv']);
            }

            // Renome les fichiers complémentaires en leur mettant une valeure crypté
            if ($row['fichiersComplementaires'] != null) {
                $data = json_decode($row['fichiersComplementaires'], true);
                $i = 0;
                foreach ($data as $unFichier) {
                    foreach ($unFichier as $f) {
                        $extensionFCcUpload = strtolower(substr(strrchr($f, '.'), 1));
                        if (file_exists('fichiersComp/' . $row['nom'] . '_' . $row['prenom'] . '_' . $row['id'] . '-' . $i . '.' . $extensionFCcUpload)) {
                            rename('fichiersComp/' . $row['nom'] . '_' . $row['prenom'] . '_' . $row['id'] . '-' . $i . '.' . $extensionFCcUpload, $f);
                            // Suppression des fichiers complémentaires
                            unlink($f);
                        }
                        $i++;
                    }
                }
            }

            // Requête delete qui supprime une candidature dans la table postuler
            $sql = 'DELETE FROM postuler';
            $sql .= ' WHERE idCandidat = :idC;';
            $stmt = self::$cnx->prepare($sql);
            $stmt->bindParam(':idC', $idCandidat, PDO::PARAM_INT);
            $stmt->execute();

            // Requête delete qui supprime un candidat dans la table candidat
            $sql = 'DELETE FROM candidat';
            $sql .= ' WHERE id = :idC;';
            $stmt = self::$cnx->prepare($sql);
            $stmt->bindParam(':idC', $idCandidat, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    /**
     * addCandidat
     * ajoute le candidat avec les valeurs saisies en paramètre
     *
     * @param string
     * @param string
     * @param string
     * @param int
     * @param string
     * @param string
     * @param string
     * @param string
     * @param int
     * @param int
     * @param int
     * @param int
     * @return string
     */
    public static function addCandidat(string $nom, string $prenom, string $email, int $tel, string $dateN, string $dateI, string $adresse, string $ville, int $cp, int $note, int $idStatut, int $poste): string
    {
        try {
            if (self::$cnx == null) {
                self::$cnx = DbManager::getConnexion();
            }

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

            // Met en majuscule la première lettre de la ville
            $ville = ucfirst($ville);

            // On vérifie si le candidat s'est déjà inscrit au poste sélectionné
            $sql = 'SELECT C.id, nom, prenom, email FROM candidat C JOIN postuler PO ON PO.idCandidat = C.id WHERE email = :param_email and idPoste = :param_idP';
            $stmt = self::$cnx->prepare($sql);
            $stmt->bindParam(':param_email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':param_idP', $poste, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->rowCount();

            // Si la requête renvoie un 0 alors le candidat n'a pas postuler pour le poste sélectionné
            if ($row == 0) {

                // Requête insert qui insère un nouveau candidat
                $sql = 'INSERT INTO `candidat` (`nom`, `prenom`, `email`, `numTel`, `cp`, `ville`, `adresse`, `dateNaissance`, `dateInscription`, `note`, `cv`, `fichiersComplementaires`, `idStatut`) VALUES';
                $sql .= ' (:nom, :prenom, :email, :num, :cp, :ville, :adresse, :dateN, :dateI, :note, :cv, :fC, :idStatut);';
                $stmt = self::$cnx->prepare($sql);
                $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
                $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->bindParam(':num', $tel, PDO::PARAM_INT);
                $stmt->bindParam(':cp', $cp, PDO::PARAM_INT);
                $stmt->bindParam(':ville', $ville, PDO::PARAM_STR);
                $stmt->bindParam(':adresse', $adresse, PDO::PARAM_STR);
                $stmt->bindParam(':dateN', $dateN, PDO::PARAM_STR);
                $stmt->bindParam(':dateI', $dateI, PDO::PARAM_STR);
                $stmt->bindParam(':note', $note, PDO::PARAM_INT);
                $stmt->bindParam(':cv', $cv, PDO::PARAM_STR);
                $stmt->bindParam(':fC', $jsonFC, PDO::PARAM_STR);
                $stmt->bindParam(':idStatut', $idStatut, PDO::PARAM_INT);
                $stmt->execute();

                // Requête select qui récupère l'id du candidat créé
                $sql = 'SELECT MAX(id) AS idC FROM candidat';
                $stmt = self::$cnx->prepare($sql);
                $stmt->execute();

                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                $row = $stmt->fetch();
                $idCandidat = $row['idC'];

                // Requête insert qui insère une candidature pour un poste
                $sql = "INSERT INTO `postuler` (`idCandidat`, `idPoste`) VALUES
                (:idCandidat, :idPoste);";
                $stmt = self::$cnx->prepare($sql);
                $stmt->bindParam(':idCandidat', $idCandidat, PDO::PARAM_INT);
                $stmt->bindParam(':idPoste', $poste, PDO::PARAM_INT);

                // Exécution de la requête
                $stmt->execute();

                // Message de succès le candidat a été ajouté
                $mess = '<div class="col-4 alert alert-success mt-5">
                <strong>Succès</strong> Le candidat a été ajouté !
                </div>';
            } else {
                // Message d'erreur, l'utilisateur existe déjà
                $mess = '<div class="col-4 alert alert-danger mt-5">
                <strong>Erreur</strong> Le candidat a déjà postulé pour ce poste
                </div>';
            }

            return $mess;
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    /**
     * editCandidat
     * modifie le candidat avec les valeurs saisies en paramètre
     *
     * @param int
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     * @param int
     * @return void
     */
    public static function editCandidat(int $idCandidat, string $nom, string $prenom, string $email, string $tel, string $dateN, string $adresse, string $ville, int $cp): void
    {
        try {
            if (self::$cnx == null) {
                self::$cnx = DbManager::getConnexion();
            }

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

            // Met en majuscule la première lettre de la ville
            $ville = ucfirst($ville);

            // Requête update qui modifie les valeurs du candidat
            $sql = 'UPDATE candidat SET nom = :nom, prenom = :prenom, email = :email, numTel = :tel, dateNaissance = :dateN, adresse = :adresse, ville = :ville, cp = :cp';
            $sql .= ' WHERE id = :idCandidat';
            $stmt = self::$cnx->prepare($sql);
            $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':tel', $tel, PDO::PARAM_INT);
            $stmt->bindParam(':dateN', $dateN, PDO::PARAM_STR);
            $stmt->bindParam(':adresse', $adresse, PDO::PARAM_STR);
            $stmt->bindParam(':ville', $ville, PDO::PARAM_STR);
            $stmt->bindParam(':cp', $cp, PDO::PARAM_INT);
            $stmt->bindParam(':idCandidat', $idCandidat, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    /**
     * editInfoCandidat
     * modifie le candidat avec les valeurs saisies en paramètre
     *
     * @param int
     * @param int
     * @return void
     */
    public static function editInfoCandidat(int $idCandidat, int $note): void
    {
        try {
            if (self::$cnx == null) {
                self::$cnx = DbManager::getConnexion();
            }

            // Requête update qui modifie la note du candidat
            $sql = 'UPDATE candidat SET note = :note';
            $sql .= ' WHERE id = :idCandidat;';
            $stmt = self::$cnx->prepare($sql);
            $stmt->bindParam(':note', $note, PDO::PARAM_INT);
            $stmt->bindParam(':idCandidat', $idCandidat, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    /**
     * getLesCandidats
     * récupère dans la bbd tous les candidats
     *
     * @return array
     */
    public static function getLesCandidats(): array
    {
        try {
            if (self::$cnx == null) {
                self::$cnx = DbManager::getConnexion();
            }

            // Requête select qui récupère toutes les informations des candidats
            $sql = 'SELECT C.id, nom, prenom, email, numTel, cp, ville, adresse, dateNaissance, dateInscription, note, cv, fichiersComplementaires, designation, S.libelle';
            $sql .= ' FROM candidat C';
            $sql .= ' JOIN postuler PO on PO.idCandidat = C.id';
            $sql .= ' JOIN poste P on P.id = PO.idPoste';
            $sql .= ' JOIN statut S on S.id = C.idStatut';

            $stmt = self::$cnx->prepare($sql);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_OBJ);
            while ($row = $stmt->fetch()) {

                self::$unCandidat = new Candidat();
                self::$unCandidat->setId($row->id);
                self::$unCandidat->setNom($row->nom);
                self::$unCandidat->setPrenom($row->prenom);
                self::$unCandidat->setEmail($row->email);
                self::$unCandidat->setNumTel($row->numTel);
                self::$unCandidat->setCp($row->cp);
                self::$unCandidat->setVille($row->ville);
                self::$unCandidat->setAdresse($row->adresse);
                $laDateNaissance = new DateTime($row->dateNaissance);
                self::$unCandidat->setDateNaissance($laDateNaissance);
                $laDateInscription = new DateTime($row->dateInscription);
                self::$unCandidat->setDateInscription($laDateInscription);
                self::$unCandidat->setNote($row->note);
                self::$unCandidat->setCv($row->cv);
                self::$unCandidat->setFichiersComplementaires($row->fichiersComplementaires);
                self::$unCandidat->setDesiPoste($row->designation);
                self::$unCandidat->setLibStatut($row->libelle);
                self::$lesCandidats[] = self::$unCandidat;

                // Renome le cv en lui mettant une valeure crypté
                if (file_exists('cv/' . self::$unCandidat->getNom() . '_' . self::$unCandidat->getPrenom() . '_' . self::$unCandidat->getId() . '.pdf')) {
                    rename('cv/' . self::$unCandidat->getNom() . '_' . self::$unCandidat->getPrenom() . '_' . self::$unCandidat->getId() . '.pdf', self::$unCandidat->getCv());
                }

                // Renome les fichiers complémentaires en leur mettant une valeure crypté
                if (self::$unCandidat->getFichiersComplementaires() != null) {
                    $data = json_decode(self::$unCandidat->getFichiersComplementaires(), true);
                    $i = 0;
                    foreach ($data as $unFichier) {
                        foreach ($unFichier as $f) {
                            $extensionFCcUpload = strtolower(substr(strrchr($f, '.'), 1));
                            if (file_exists('fichiersComp/' . self::$unCandidat->getNom() . '_' . self::$unCandidat->getPrenom() . '_' . self::$unCandidat->getId() . '-' . $i . '.' . $extensionFCcUpload)) {
                                rename('fichiersComp/' . self::$unCandidat->getNom() . '_' . self::$unCandidat->getPrenom() . '_' . self::$unCandidat->getId() . '-' . $i . '.' . $extensionFCcUpload, $f);
                            }
                            $i++;
                        }
                    }
                }
            }

            return self::$lesCandidats;
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    /**
     * getNbCandidatS
     * récupère le nombre de candidat inscrit en fonction du statut
     *
     * @param int
     * @return int
     */
    public static function getNbCandidat(int $idStatut): int
    {
        try {
            if (self::$cnx == null) {
                self::$cnx = DbManager::getConnexion();
            }

            if ($idStatut > 0) {

                // Requête select qui récupère le nombre de candidats pour un statut
                $sql = 'SELECT COUNT(id)';
                $sql .= ' FROM candidat';
                $sql .= ' WHERE idStatut = :idS';
                $stmt = self::$cnx->prepare($sql);
                $stmt->bindParam(':idS', $idStatut, PDO::PARAM_INT);
                $stmt->execute();

                $count = $stmt->fetch(PDO::FETCH_NUM)[0];
            } else {

                // Requête select qui récupère le nombre de candidats total
                $sql = 'SELECT COUNT(id)';
                $sql .= ' FROM candidat';
                $stmt = self::$cnx->prepare($sql);
                $stmt->execute();

                $count = $stmt->fetch(PDO::FETCH_NUM)[0];
            }

            return $count;
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    /**
     * existCandidat
     * vérifie si le candidat existe
     *
     * @param int
     * @return bool
     */
    public static function existCandidat(int $idCandidat): bool
    {
        try {
            if (self::$cnx == null) {
                self::$cnx = DbManager::getConnexion();
            }
            $exist = false;

            // Requête select qui récupère les informations du candidat
            $sql = 'SELECT id, nom, prenom, email FROM candidat';
            $sql .= ' WHERE id = :idC';
            $stmt = self::$cnx->prepare($sql);
            $stmt->bindParam(':idC', $idCandidat, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->rowCount();

            // Le candidat existe
            if ($row == 1) {
                $exist = true;
            }

            return $exist;
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    /**
     * viderStatut
     * supprime tous les candidats d'un statut
     *
     * @param int
     * @return void
     */
    public static function viderStatut(int $idStatut): void
    {
        try {
            if (self::$cnx == null) {
                self::$cnx = DbManager::getConnexion();
            }

            // Requête select qui récupère le cv et les fichiers complémentaires des candidats pour le statut
            $sql = 'SELECT cv, fichiersComplementaires';
            $sql .= ' FROM candidat';
            $sql .= ' WHERE idStatut = :idS';
            $stmt = self::$cnx->prepare($sql);
            $stmt->bindParam(':idS', $idStatut, PDO::PARAM_INT);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            while ($row = $stmt->fetch()) {

                $cv = $row['cv'];
                if (file_exists($cv)) {
                    // Suppression du cv
                    unlink($cv);
                }
                $fichiersComp = $row['fichiersComplementaires'];

                $fc = json_decode($fichiersComp);
                foreach ($fc as $f) {
                    foreach ($f as $unf) {
                        if (file_exists($unf)) {
                            // Suppression des fichiers complémentaires
                            unlink($unf);
                        }
                    }
                }
            }

            // Requête delete qui supprime les candidatures pour le statut
            $sql = 'DELETE PO FROM postuler PO';
            $sql .= ' JOIN candidat C on C.id = PO.idCandidat';
            $sql .= ' WHERE idStatut = :idS;';
            $stmt = self::$cnx->prepare($sql);
            $stmt->bindParam(':idS', $idStatut, PDO::PARAM_INT);
            $stmt->execute();

            // Requête delete qui supprime les candidats pour le statut
            $sql = 'DELETE FROM candidat';
            $sql .= ' WHERE idStatut = :idS';
            $stmt = self::$cnx->prepare($sql);
            $stmt->bindParam(':idS', $idStatut, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }
}
