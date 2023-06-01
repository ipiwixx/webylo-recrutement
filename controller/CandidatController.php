<?php

/**
 * /controller/CandidatController.php
 *
 * Contrôleur pour l'entité Candidat
 *
 * @author A. Espinoza
 * @date 03/2023
 */

class CandidatController extends Controller
{

    /**
     * Action qui affiche les candidats
     * params : tableau des paramètres
     */
    public static function show($params)
    {

        // Vérifie qu'il y a bien un id statut dans l'url
        if (isset($_GET['idS'])) {

            // Filtre les variables GET pour enlever les caractères indésirables
            $idStatut = nettoyer(filter_var($_GET['idS'], FILTER_VALIDATE_INT));

            $lesCandidats = CandidatManager::getLesCandidatsByStatut($idStatut);
        } else {
            $lesCandidats = CandidatManager::getLesCandidats();
        }

        $view = ROOT . '/view/dashboard.php';
        // appelle la vue
        $params = array();
        $params['lesCandidats'] = $lesCandidats;
        self::render($view, $params);
    }

    /**
     * Action qui teste l'inscription du candidat
     * params : tableau des paramètres
     */
    public static function inscription($params)
    {
        $mess = '';

        if (isset($_FILES['cv']['name'])) {

            // Taille maximum pour le fichier pdf
            $maxsizes = 95144071;

            // Extension valide pour le cv
            $extensionsCVValides = array('pdf');

            // Extensions valide pour les fichiers complémentaires
            $extensionsFCValides = array('pdf', 'png', 'jpg', 'jpeg', 'doc', 'docx');

            // Récupère l'extension du cv
            $extensionsCVUpload = strtolower(substr(strrchr($_FILES['cv']['name'], '.'), 1));

            // Vérifie que le formulaire a été soumis
            if (isset($_POST['inscriptionSubmit'])) {

                // Vérifie que tous les champs sont remplis
                if (isset($_POST['nom']) && !empty($_POST['nom']) && isset($_POST['prenom']) && !empty($_POST['prenom']) && isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['tel']) && !empty($_POST['tel']) && isset($_POST['date']) && !empty($_POST['date']) && isset($_POST['adresse']) && !empty($_POST['adresse']) && isset($_POST['ville']) && !empty($_POST['ville']) && isset($_POST['cp']) && !empty($_POST['cp']) && isset($_POST['poste']) && !empty($_POST['poste'])) {

                    // Filtre les input de type poste pour enlever les caractères indésirables
                    $nom = nettoyer(filter_input(INPUT_POST, 'nom', FILTER_DEFAULT));
                    $prenom = nettoyer(filter_input(INPUT_POST, 'prenom', FILTER_DEFAULT));
                    $email = nettoyer(filter_input(INPUT_POST, 'email', FILTER_DEFAULT));
                    $tel = nettoyer(filter_input(INPUT_POST, 'tel', FILTER_DEFAULT));
                    $dateN = nettoyer(filter_input(INPUT_POST, 'date', FILTER_DEFAULT));
                    $adresse = nettoyer(filter_input(INPUT_POST, 'adresse', FILTER_DEFAULT));
                    $ville = nettoyer(filter_input(INPUT_POST, 'ville', FILTER_DEFAULT));
                    $cp = nettoyer(filter_var($_POST['cp'], FILTER_VALIDATE_INT));
                    $note = 0;
                    $poste = nettoyer(filter_var($_POST['poste'], FILTER_VALIDATE_INT));

                    if (strlen($nom) <= 64) { // Vérifie que la longueur du nom soit inférieur ou égal à 128
                        if (strlen($prenom) <= 64) { // Vérifie que la longueur du prénom soit inférieur ou égal à 128
                            if (strlen($email) <= 128) { // Vérifie que la longueur de l'email soit inférieur ou égal à 128
                                if (strlen($tel) == 10) { // Vérifie que la longueur du téléphone soit de 10
                                    if (strlen($adresse) <= 128) { // Vérifie que la longueur de l'adrese soit inférieur ou égal à 128
                                        if (strlen($ville) <= 64) { // Vérifie que la longueur de la ville soit inférieur ou égal à 64
                                            if (strlen($cp) == 5) { // Vérifie que la longueur du code postal soit de 5
                                                if (filter_var($email, FILTER_VALIDATE_EMAIL)) { // Vérifie si l'email est de la bonne forme
                                                    if (preg_match('/^[0-9]{10}$/', $tel)) { // Vérifie que le numéro de téléphone est valide
                                                        if (preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $dateN)) { // Vérifie que la date de naissance est valide
                                                            if ($_FILES['cv']['error'] == 0) { // Vérifie si il y a une erreur de transfert du cv
                                                                if ($_FILES['cv']['size'] < $maxsizes) { // Vérifie que le cv soit bien inférieur à la taille maximum
                                                                    if (in_array($extensionsCVUpload, $extensionsCVValides)) { // Vérifie si l'extension est correcte

                                                                        $mess = CandidatManager::inscription($nom, $prenom, $email, $tel, $dateN, $adresse, $ville, $cp, $note, $poste, $maxsizes);
                                                                    } else {
                                                                        // Message d'erreur, l'extension n'est pas correcte
                                                                        $mess = '<div class="col-4 alert alert-danger">
                                                                        <strong>Erreur</strong> L\'extension du CV n\'est pas correcte !
                                                                        </div>';
                                                                    }
                                                                } else {
                                                                    // Message d'erreur, fichier trop volumineux
                                                                    $mess = '<div class="col-4 alert alert-danger">
                                                                    <strong>Erreur</strong> Fichier CV trop volumineux !
                                                                    </div>';
                                                                }
                                                            } else {
                                                                // Message d'erreur, echec lors du transfert du cv
                                                                $mess = '<div class="col-4 alert alert-danger">
                                                                <strong>Erreur</strong> Echec du transfert du CV !
                                                                </div>';
                                                            }
                                                        } else {
                                                            // Message d'erreur, le numéro de téléphone n'est pas bon
                                                            $mess = '<div class="col-4 alert alert-danger">
                                                            <strong>Erreur</strong> La date de naissance n\'est pas correcte !
                                                            </div>';
                                                        }
                                                    } else {
                                                        // Message d'erreur, le numéro de téléphone n'est pas bon
                                                        $mess = '<div class="col-4 alert alert-danger">
                                                        <strong>Erreur</strong> Le numéro de téléphone n\'est pas correcte !
                                                        </div>';
                                                    }
                                                } else {
                                                    // Message d'erreur, l'email est de la mauvaise forme
                                                    $mess = '<div class="col-4 alert alert-danger">
                                                    <strong>Erreur</strong> Email non valide !
                                                    </div>';
                                                }
                                            } else {
                                                // Message d'erreur le code postal est trop long
                                                $mess = '<div class="col-4 alert alert-danger">
                                                <strong>Erreur</strong> Le code postal est trop long !
                                                </div>';
                                            }
                                        } else {
                                            // Message d'erreur la ville est trop longue
                                            $mess = '<div class="col-4 alert alert-danger">
                                            <strong>Erreur</strong> La ville est trop longue !
                                            </div>';
                                        }
                                    } else {
                                        // Message d'erreur l'adresse est trop longue
                                        $mess = '<div class="col-4 alert alert-danger">
                                        <strong>Erreur</strong> L\'adresse est trop longue !
                                        </div>';
                                    }
                                } else {
                                    // Message d'erreur le numéro de téléphone est trop long
                                    $mess = '<div class="col-4 alert alert-danger">
                                    <strong>Erreur</strong> Le numéro de téléphone est trop long !
                                    </div>';
                                }
                            } else {
                                // Message d'erreur le mail est trop long
                                $mess = '<div class="col-4 alert alert-danger">
                                <strong>Erreur</strong> L\'email est trop long !
                                </div>';
                            }
                        } else {
                            // Message d'erreur le prénom est trop long
                            $mess = '<div class="col-4 alert alert-danger">
                            <strong>Erreur</strong> Le prénom est trop long !
                            </div>';
                        }
                    } else {
                        // Message d'erreur le nom est trop long
                        $mess = '<div class="col-4 alert alert-danger">
                        <strong>Erreur</strong> Le nom est trop long !
                        </div>';
                    }
                }
            }
        }

        $lesPostes = PosteManager::getLesPostesNonDesac();

        // appelle la vue
        $view = ROOT . '/view/inscription.php';
        $params = array();
        $params['mess'] = $mess;
        $params['lesPostes'] = $lesPostes;
        self::render($view, $params);
    }

    /**
     * Action qui ajoute un candidat
     * params : tableau des paramètres
     */
    public static function add($params)
    {
        $mess = '';

        /* // Taille maximum pour le fichier pdf
        $maxsizes = 95144071;

        // Extension valide pour le cv
        $extensionsCVValides = array( 'pdf');
        
        // Extensions valide pour les fichiers complémentaires
        $extensionsFCValides = array( 'pdf', 'png', 'jpg', 'jpeg', 'doc', 'docx'); */

        // Vérifie que l'utilisateur est connecté
        if (isset($_SESSION['user'])) {

            // Vérifie que le formulaire a été soumis
            if (isset($_POST['addSubmit'])) {

                // Vérifie que tous les champs sont remplis
                if (!empty($_POST['nom']) && isset($_POST['nom']) && !empty($_POST['prenom']) && isset($_POST['prenom']) && !empty($_POST['email']) && isset($_POST['email']) && !empty($_POST['tel']) && isset($_POST['tel']) && !empty($_POST['cp']) && isset($_POST['cp']) && !empty($_POST['ville']) && isset($_POST['ville']) && !empty($_POST['adresse']) && isset($_POST['adresse']) && !empty($_POST['date']) && isset($_POST['date']) && isset($_POST['poste']) && !empty($_POST['poste'])) {

                    // Filtre les input de type poste pour enlever les caractères indésirables
                    $nom = nettoyer(filter_input(INPUT_POST, 'nom', FILTER_DEFAULT));
                    $prenom = nettoyer(filter_input(INPUT_POST, 'prenom', FILTER_DEFAULT));
                    $email = nettoyer(filter_input(INPUT_POST, 'email', FILTER_DEFAULT));
                    $tel = nettoyer(filter_input(INPUT_POST, 'tel', FILTER_DEFAULT));
                    $dateN = nettoyer(filter_input(INPUT_POST, 'date', FILTER_DEFAULT));
                    date_default_timezone_set('Europe/Paris');
                    $dateI = new DateTime();
                    $dateI = $dateI->format('Y-m-d');
                    $adresse = nettoyer(filter_input(INPUT_POST, 'adresse', FILTER_DEFAULT));
                    $ville = nettoyer(filter_input(INPUT_POST, 'ville', FILTER_DEFAULT));
                    $cp = nettoyer(filter_var($_POST['cp'], FILTER_VALIDATE_INT));
                    $note = 0;
                    $poste = nettoyer(filter_var($_POST['poste'], FILTER_VALIDATE_INT));
                    $idStatut = 1;

                    // Récupère l'extension du cv
                    //$extensionsCVUpload = strtolower(  substr(  strrchr($_FILES['cvAdd']['name'], '.')  ,1)  );

                    if (strlen($nom) <= 64) { // Vérifie que la longueur du nom soit inférieur ou égal à 128
                        if (strlen($prenom) <= 64) { // Vérifie que la longueur du prénom soit inférieur ou égal à 128
                            if (strlen($email) <= 128) { // Vérifie que la longueur de l'email soit inférieur ou égal à 128
                                if (strlen($tel) == 10) { // Vérifie que la longueur du téléphone soit de 10
                                    if (strlen($adresse) <= 128) { // Vérifie que la longueur de l'adrese soit inférieur ou égal à 128
                                        if (strlen($ville) <= 64) { // Vérifie que la longueur de la ville soit inférieur ou égal à 64
                                            if (strlen($cp) == 5) { // Vérifie que la longueur du code postal soit de 5
                                                if (filter_var($email, FILTER_VALIDATE_EMAIL)) { // Vérifie si l'email est de la bonne forme
                                                    if (preg_match('/^[0-9]{10}$/', $tel)) { // Vérifie que le numéro de téléphone est valide
                                                        if (preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $dateN)) { // Vérifie que la date de naissance est valide
                                                            /* if($_FILES['cv']['error'] == 0) { // Vérifie si il y a une erreur de transfert du cv
                                                                if($_FILES['cv']['size'] < $maxsizes) { // Vérifie que le cv soit bien inférieur à la taille maximum
                                                                    if(in_array($extensionsCVUpload,$extensionsCVValides)) { // Vérifie si l'extension est correcte */

                                                            $mess = CandidatManager::addCandidat($nom, $prenom, $email, $tel, $dateN, $dateI, $adresse, $ville, $cp, $note, $idStatut, $poste);

                                                            /* } else {
                                                                // Message d'erreur, l'extension n'est pas correcte
                                                                $mess = '<div class=" col-4 alert alert-danger">
                                                                <strong>Erreur</strong> L\'extension du CV n\'est pas correcte !
                                                                </div>';
                                                            }
                                                        } else {
                                                            // Message d'erreur, fichier trop volumineux
                                                            $mess = '<div class=" col-4 alert alert-danger">
                                                            <strong>Erreur</strong> Fichier CV trop volumineux !
                                                            </div>';
                                                        }
                                                    } else {
                                                        // Message d'erreur, echec lors du transfert du cv
                                                        $mess = '<div class=" col-4 alert alert-danger">
                                                        <strong>Erreur</strong> Echec du transfert du CV !
                                                        </div>';
                                                    } */
                                                        } else {
                                                            // Message d'erreur, le numéro de téléphone n'est pas bon
                                                            $mess = '<div class="col-4 alert alert-danger mt-5">
                                                            <strong>Erreur</strong> La date de naissance n\'est pas correcte !
                                                            </div>';
                                                        }
                                                    } else {
                                                        // Message d'erreur, le numéro de téléphone n'est pas bon
                                                        $mess = '<div class="col-4 alert alert-danger mt-5">
                                                        <strong>Erreur</strong> Le numéro de téléphone n\'est pas correcte !
                                                        </div>';
                                                    }
                                                } else {
                                                    // Message d'erreur, l'email est de la mauvaise forme
                                                    $mess = '<div class="col-4 alert alert-danger mt-5">
                                                    <strong>Erreur</strong> Email non valide !
                                                    </div>';
                                                }
                                            } else {
                                                // Message d'erreur le code postal est trop long
                                                $mess = '<div class="col-4 alert alert-danger mt-5">
                                                <strong>Erreur</strong> Le code postal est trop long !
                                                </div>';
                                            }
                                        } else {
                                            // Message d'erreur la ville est trop longue
                                            $mess = '<div class="col-4 alert alert-danger mt-5">
                                            <strong>Erreur</strong> La ville est trop longue !
                                            </div>';
                                        }
                                    } else {
                                        // Message d'erreur l'adresse est trop longue
                                        $mess = '<div class="col-4 alert alert-danger mt-5">
                                        <strong>Erreur</strong> L\'adresse est trop longue !
                                        </div>';
                                    }
                                } else {
                                    // Message d'erreur le numéro de téléphone est trop long
                                    $mess = '<div class="col-4 alert alert-danger mt-5">
                                    <strong>Erreur</strong> Le numéro de téléphone est trop long !
                                    </div>';
                                }
                            } else {
                                // Message d'erreur le mail est trop long
                                $mess = '<div class="col-4 alert alert-danger mt-5">
                                <strong>Erreur</strong> L\'email est trop long !
                                </div>';
                            }
                        } else {
                            // Message d'erreur le prénom est trop long
                            $mess = '<div class="col-4 alert alert-danger mt-5">
                            <strong>Erreur</strong> Le prénom est trop long !
                            </div>';
                        }
                    } else {
                        // Message d'erreur le nom est trop long
                        $mess = '<div class="col-4 alert alert-danger mt-5">
                        <strong>Erreur</strong> Le nom est trop long !
                        </div>';
                    }
                } else {
                    // Message d'erreur le candidat n'a pas été ajouté
                    $mess = '<div class="col-4 alert alert-danger mt-5">
                    <strong>Erreur</strong> Veuillez remplir tous les champs !
                    </div>';
                }
            }
        }
        $lesPostes = PosteManager::getLesPostes();

        $view = ROOT . '/view/addCandidat.php';
        // appelle la vue
        $params = array();
        $params['mess'] = $mess;
        $params['lesPostes'] = $lesPostes;
        self::render($view, $params);
    }

    /**
     * Action qui supprime un candidat
     * params : tableau des paramètres
     */
    public static function delete($params)
    {

        // Vérifie que l'utilisateur est connecté
        if (isset($_SESSION['user'])) {

            // Vérifie qu'il y a bien un id candidat dans l'url
            if (isset($_GET['idC'])) {

                // Filtre les variables GET pour enlever les caractères indésirables
                $idCandidat = nettoyer(filter_var($_GET['idC'], FILTER_VALIDATE_INT));

                CandidatManager::deleteCandidat($idCandidat);
            }
        }

        $view = ROOT . '/view/dashboard.php';
        // appelle la vue
        $params = array();
        self::render($view, $params);
    }

    /**
     * Action qui moddifie un candidat
     * params : tableau des paramètres
     */
    public static function edit($params)
    {
        $mess = '';

        // Vérifie que l'utilisateur est connecté
        if (isset($_SESSION['user'])) {

            // Vérifie qu'il y a bien un id candidat dans l'url
            if (isset($_GET['idC'])) {

                // Filtre les variables GET pour enlever les caractères indésirables
                $idCandidat = nettoyer(filter_var($_GET['idC'], FILTER_VALIDATE_INT));

                $exist = CandidatManager::existCandidat($idCandidat);

                if ($exist == true) {
                    $unCandidat = CandidatManager::getUnCandidatById($idCandidat);
                } else {
                    $unCandidat = null;
                }
            }

            $lesPostes = PosteManager::getLesPostes();
            $lesStatuts = StatutManager::getLesStatuts();

            // Vérifie que le formulaire a été soumis
            if (isset($_POST['editSubmit'])) {

                // Vérifie que tous les champs sont remplis
                if (!empty($_POST['nom']) && isset($_POST['nom']) && !empty($_POST['prenom']) && isset($_POST['prenom']) && !empty($_POST['email']) && isset($_POST['email']) && !empty($_POST['tel']) && isset($_POST['tel']) && !empty($_POST['cp']) && isset($_POST['cp']) && !empty($_POST['ville']) && isset($_POST['ville']) && !empty($_POST['adresse']) && isset($_POST['adresse']) && !empty($_POST['date']) && isset($_POST['date']) && isset($_GET['idC'])) {

                    // Filtre les input de type poste pour enlever les caractères indésirables
                    $nom = nettoyer(filter_input(INPUT_POST, 'nom', FILTER_DEFAULT));
                    $prenom = nettoyer(filter_input(INPUT_POST, 'prenom', FILTER_DEFAULT));
                    $email = nettoyer(filter_input(INPUT_POST, 'email', FILTER_DEFAULT));
                    $tel = nettoyer(filter_input(INPUT_POST, 'tel', FILTER_DEFAULT));
                    $dateN = nettoyer(filter_input(INPUT_POST, 'date', FILTER_DEFAULT));
                    date_default_timezone_set('Europe/Paris');
                    $dateI = new DateTime();
                    $dateI = $dateI->format('Y-m-d');
                    $adresse = nettoyer(filter_input(INPUT_POST, 'adresse', FILTER_DEFAULT));
                    $ville = nettoyer(filter_input(INPUT_POST, 'ville', FILTER_DEFAULT));
                    $cp = nettoyer(filter_var($_POST['cp'], FILTER_VALIDATE_INT));
                    $idCandidat = nettoyer(filter_var($_GET['idC'], FILTER_VALIDATE_INT));

                    if (strlen($nom) <= 64) { // Vérifie que la longueur du nom soit inférieur ou égal à 128
                        if (strlen($prenom) <= 64) { // Vérifie que la longueur du prénom soit inférieur ou égal à 128
                            if (strlen($email) <= 128) { // Vérifie que la longueur de l'email soit inférieur ou égal à 128
                                if (strlen($tel) == 10) { // Vérifie que la longueur du téléphone soit de 10
                                    if (strlen($adresse) <= 128) { // Vérifie que la longueur de l'adrese soit inférieur ou égal à 128
                                        if (strlen($ville) <= 64) { // Vérifie que la longueur de la ville soit inférieur ou égal à 64
                                            if (strlen($cp) == 5) { // Vérifie que la longueur du code postal soit de 5

                                                CandidatManager::editCandidat($idCandidat, $nom, $prenom, $email, $tel, $dateN, $adresse, $ville, $cp);

                                                // Message de succès le candidat a été modifié
                                                $mess = '<div class="col-4 alert alert-success">
                                                <strong>Succès</strong> Le candidat a été modifé !
                                                </div>';

                                                if (isset($_POST['note']) && !empty($_POST['note'])) {
                                                    $note = nettoyer(filter_var($_POST['note'], FILTER_VALIDATE_INT));
                                                    CandidatManager::editInfoCandidat($idCandidat, $note);
                                                }
                                            } else {
                                                // Message d'erreur le code postal est trop long
                                                $mess = '<div class="col-4 alert alert-danger">
                                                <strong>Erreur</strong> Le code postal est trop long !
                                                </div>';
                                            }
                                        } else {
                                            // Message d'erreur la ville est trop longue
                                            $mess = '<div class="col-4 alert alert-danger">
                                            <strong>Erreur</strong> La ville est trop longue !
                                            </div>';
                                        }
                                    } else {
                                        // Message d'erreur l'adresse est trop longue
                                        $mess = '<div class="col-4 alert alert-danger">
                                        <strong>Erreur</strong> L\'adresse est trop longue !
                                        </div>';
                                    }
                                } else {
                                    // Message d'erreur le numéro de téléphone est trop long
                                    $mess = '<div class="col-4 alert alert-danger">
                                    <strong>Erreur</strong> Le numéro de téléphone est trop long !
                                    </div>';
                                }
                            } else {
                                // Message d'erreur le mail est trop long
                                $mess = '<div class="col-4 alert alert-danger">
                                <strong>Erreur</strong> L\'email est trop long !
                                </div>';
                            }
                        } else {
                            // Message d'erreur le nom est trop long
                            $mess = '<div class="col-4 alert alert-danger">
                            <strong>Erreur</strong> Le nom est trop long !
                            </div>';
                        }
                    } else {
                        // Message d'erreur le prenom est trop long
                        $mess = '<div class="col-4 alert alert-danger">
                        <strong>Erreur</strong> Le prenom est trop long !
                        </div>';
                    }
                } else {
                    // Message d'erreur le candiat n'a pas été modifié
                    $mess = '<div class="col-4 alert alert-danger">
                    <strong>Erreur</strong> Veuillez remplir tous les champs !
                    </div>';
                }
            }
        }

        $view = ROOT . '/view/editCandidat.php';
        // appelle la vue
        $params = array();
        $params['unCandidat'] = $unCandidat;
        $params['lesStatuts'] = $lesStatuts;
        $params['lesPostes'] = $lesPostes;
        $params['exist'] = $exist;
        $params['mess'] = $mess;
        self::render($view, $params);
    }

    /**
     * Action qui refuse un candidat
     * params : tableau des paramètres
     */
    public static function refus($params)
    {

        // Vérifie que l'utilisateur est connecté
        if (isset($_SESSION['user'])) {

            // Vérifie qu'il y a bien un id candidat dans l'url
            if (isset($_GET['idC'])) {

                // Filtre les variables GET pour enlever les caractères indésirables
                $idCandidat = nettoyer(filter_var($_GET['idC'], FILTER_VALIDATE_INT));

                CandidatManager::refusCandidat($idCandidat);
            }
        }

        $view = ROOT . '/view/dashboard.php';
        // appelle la vue
        $params = array();
        self::render($view, $params);
    }

    /**
     * Action qui accepte un candidat
     * params : tableau des paramètres
     */
    public static function accepte($params)
    {

        // Vérifie que l'utilisateur est connecté
        if (isset($_SESSION['user'])) {

            // Vérifie qu'il y a bien un id candidat dans l'url
            if (isset($_GET['idC'])) {

                // Filtre les variables GET pour enlever les caractères indésirables
                $idCandidat = nettoyer(filter_var($_GET['idC'], FILTER_VALIDATE_INT));

                CandidatManager::accepteCandidat($idCandidat);
            }
        }

        $view = ROOT . '/view/dashboard.php';
        // appelle la vue
        $params = array();
        self::render($view, $params);
    }

    /**
     * Action qui supprime tous les candidats d'un statut
     * params : tableau des paramètres
     */
    public static function viderStatut($params)
    {

        // Vérifie que l'utilisateur est connecté
        if (isset($_SESSION['user'])) {

            // Vérifie qu'il y a bien un id statut dans l'url
            if (isset($_GET['idS'])) {

                // Filtre les variables GET pour enlever les caractères indésirables
                $idStatut = nettoyer(filter_var($_GET['idS'], FILTER_VALIDATE_INT));

                CandidatManager::viderStatut($idStatut);
            }
        }

        $view = ROOT . '/view/dashboard.php';
        // appelle la vue
        $params = array();
        self::render($view, $params);
    }
}
