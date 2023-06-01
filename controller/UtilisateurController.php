<?php

/**
 * /controller/UtilisateurController.php
 *
 * Contrôleur pour l'entité Utilisateur
 *
 * @author A. Espinoza
 * @date 03/2023
 */

class UtilisateurController extends Controller
{

    /**
     * Action qui vérifie le mail pour réinitialiser le mdp
     * params : tableau des paramètres
     */
    public static function mdpOublie($params)
    {

        $error = UtilisateurManager::recupMdp();

        // appelle la vue
        $view = ROOT . '/view/mdp-oublie.php';
        $params = array();
        $params['error'] = $error;
        self::render($view, $params);
    }

    /**
     * Action qui vérifie le code
     * params : tableau des paramètres
     */
    public static function mdpCode($params)
    {

        $error = UtilisateurManager::recupMdp();

        // appelle la vue
        $view = ROOT . '/view/code-mdp-oublie.php';
        $params = array();
        $params['error'] = $error;
        self::render($view, $params);
    }

    /**
     * Action qui change le mdp
     * params : tableau des paramètres
     */
    public static function mdpChange($params)
    {

        $error = UtilisateurManager::recupMdp();

        // appelle la vue
        $view = ROOT . '/view/change-mdp.php';
        $params = array();
        $params['error'] = $error;
        self::render($view, $params);
    }

    /**
     * Action qui teste la connexion de l'utilisateur
     * params : tableau des paramètres
     */
    public static function connexion($params)
    {
        $mess = '';

        // Vérifie que le formulaire a été soumis
        if (isset($_POST['loginSumbit'])) {

            // Vérifie que tous les champs sont remplis
            if (isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['mdp']) && !empty($_POST['mdp'])) {
                // Filtre les input de type poste pour enlever les caractères indésirables
                $email = nettoyer(filter_input(INPUT_POST, 'email', FILTER_DEFAULT));
                $mdp = nettoyer(filter_input(INPUT_POST, 'mdp', FILTER_DEFAULT));

                $mess = UtilisateurManager::testLaConnexion($email, $mdp);
            }
        }

        // appelle la vue
        $view = ROOT . '/view/connexion.php';
        $params = array();
        $params['mess'] = $mess;
        self::render($view, $params);
    }

    /**
     * Action qui ajoute un utilisateur
     * params : tableau des paramètres
     */
    public static function add($params)
    {
        $mess = '';

        // Vérifie que l'utilisateur est connecté
        if (isset($_SESSION['user'])) {

            // Vérifie que le formulaire a été soumis
            if (isset($_POST['addSubmit'])) {

                // Vérifie que tous les champs sont remplis
                if (!empty($_POST['nom']) && isset($_POST['nom']) && !empty($_POST['prenom']) && isset($_POST['prenom']) && !empty($_POST['email']) && isset($_POST['email']) && !empty($_POST['mdp']) && isset($_POST['mdp']) && !empty($_POST['mdpc']) && isset($_POST['mdpc']) && !empty($_POST['role']) && isset($_POST['role'])) {

                    // Vérifie si les 2 mot de passes sont identiques
                    if ($_POST['mdp'] == $_POST['mdpc']) {

                        // Vérifie si l'email est de la bonne forme
                        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {

                            // Filtre les input de type poste pour enlever les caractères indésirables
                            $nom = nettoyer(filter_input(INPUT_POST, 'nom', FILTER_DEFAULT));
                            $prenom = nettoyer(filter_input(INPUT_POST, 'prenom', FILTER_DEFAULT));
                            $email = nettoyer(filter_input(INPUT_POST, 'email', FILTER_DEFAULT));
                            $mdp = nettoyer(filter_input(INPUT_POST, 'mdp', FILTER_DEFAULT));
                            $role = nettoyer(filter_input(INPUT_POST, 'role', FILTER_DEFAULT));

                            if (strlen($nom) <= 64) { // Vérifie que la longueur du nom soit inférieur ou égal à 64
                                if (strlen($prenom) <= 64) { // Vérifie que la longueur du prenom soit inférieur ou égal à 64
                                    if (strlen($email) <= 128) { // Vérifie que la longueur de l'email soit inférieur ou égal à 128

                                        UtilisateurManager::addUtilisateur($nom, $prenom, $email, $mdp, $role);
                                        // à vérifier si l'user est admin pour ajouter un user avec role admin

                                        // Message de succès l'utilisateur a été ajouté
                                        $mess = '<div class="col-4 alert alert-success">
                                        <strong>Succès</strong> L\'utilisateur a été ajouté !
                                        </div>';
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
                        } else {
                            // Message d'erreur le mail n'est pas de la bonne forme
                            $mess = '<div class="col-4 alert alert-danger">
                            <strong>Erreur</strong> Email invalide !
                            </div>';
                        }
                    } else {
                        // Message d'erreur l'utilisateur n'a pas été ajouté
                        $mess = '<div class="col-4 alert alert-danger">
                        <strong>Erreur</strong> Les mots de passes ne correspondent pas !
                        </div>';
                    }
                } else {
                    // Message d'erreur l'utilisateur n'a pas été ajouté
                    $mess = '<div class="col-4 alert alert-danger">
                    <strong>Erreur</strong> Veuillez remplir tous les champs !
                    </div>';
                }
            }
        }

        $view = ROOT . '/view/addUtilisateur.php';
        // appelle la vue
        $params = array();
        $params['mess'] = $mess;
        self::render($view, $params);
    }

    /**
     * Action qui supprime un utilisateur
     * params : tableau des paramètres
     */
    public static function delete($params)
    {
        $mess = '';

        // Vérifie que l'utilisateur est connecté
        if (isset($_SESSION['user'])) {

            // Vérifie qu'il y a bien un id utilisateur dans l'url
            if (isset($_GET['idU'])) {

                // Filtre les variables GET pour enlever les caractères indésirables
                $idUtilisateur = nettoyer(filter_var($_GET['idU'], FILTER_VALIDATE_INT));

                $unUtilisateur = UtilisateurManager::getUtilisateurById($idUtilisateur);

                if ($idUtilisateur != $_SESSION['id']) {
                    //if($unUtilisateur->getRole() == "user" and $_SESSION['user']->getRole() == "admin") {
                    UtilisateurManager::deleteUtilisateur($idUtilisateur);

                    // Message de succès l'utilsateur a été supprimé
                    $mess = '<div class="col-4 alert alert-success">
                        <strong>Succès</strong> L\'utilisateur a été supprimé !
                        </div>';
                    /* } else {
                        $mess = '<div class="col-4 alert alert-danger">
                        <strong>Erreur</strong> Vous ne disposez pas des droits pour supprimer cet utilisateur !
                        </div>';
                    }  */
                } else {
                    $mess = '<div class="col-4 alert alert-danger">
                    <strong>Erreur</strong> Vous ne pouvez pas supprimez votre compte !
                    </div>';
                }
            }
        }

        $view = ROOT . '/view/dashboard.php';
        // appelle la vue
        $params = array();
        $params['mess'] = $mess;
        self::render($view, $params);
    }

    /**
     * Action qui moddifie un utilisateur
     * params : tableau des paramètres
     */
    public static function edit($params)
    {

        // Vérifie que l'utilisateur est connecté
        if (isset($_SESSION['user'])) {

            // Vérifie qu'il y a bien un id utilisateur dans l'url
            if (isset($_GET['idU'])) {

                // Filtre les variables GET pour enlever les caractères indésirables
                $idUtilisateur = nettoyer(filter_var($_GET['idU'], FILTER_VALIDATE_INT));

                $exist = UtilisateurManager::existUtilisateur($idUtilisateur);

                if ($exist == true) {
                    $unUtilisateur = UtilisateurManager::getUtilisateurById($idUtilisateur);
                } else {
                    $unUtilisateur = null;
                }
            }
            $mess = '';
            $messMdp = '';

            // Vérifie que le formulaire a été soumis
            if (isset($_POST['editSubmit'])) {

                // Vérifie que tous les champs sont remplis
                if (!empty($_POST['nom']) && isset($_POST['nom']) && !empty($_POST['prenom']) && isset($_POST['prenom']) && !empty($_POST['email']) && isset($_POST['email']) && !empty($_POST['role']) && isset($_POST['role']) && isset($_GET['idU'])) {

                    // Filtre les input de type poste pour enlever les caractères indésirables
                    $nom = nettoyer(filter_input(INPUT_POST, 'nom', FILTER_DEFAULT));
                    $prenom = nettoyer(filter_input(INPUT_POST, 'prenom', FILTER_DEFAULT));
                    $email = nettoyer(filter_input(INPUT_POST, 'email', FILTER_DEFAULT));
                    $role = nettoyer(filter_input(INPUT_POST, 'role', FILTER_DEFAULT));
                    $idUtilisateur = nettoyer(filter_var($_GET['idU'], FILTER_VALIDATE_INT));

                    if (strlen($nom) <= 64) { // Vérifie que la longueur du nom soit inférieur ou égal à 64
                        if (strlen($prenom) <= 64) { // Vérifie que la longueur du prénom soit inférieur ou égal à 64
                            if (strlen($email) <= 128) { // Vérifie que la longueur de l'email soit inférieur ou égal à 128

                                UtilisateurManager::editUtilisateur($idUtilisateur, $nom, $prenom, $email, $role);

                                // Message de succès l'utilisateur a été modifié
                                $mess = '<div class="col-4 alert alert-success">
                                <strong>Succès</strong> L\'utilisateur a été modifé !
                                </div>';
                            } else {
                                // Message d'erreur le nom est trop long
                                $mess = '<div class="col-4 alert alert-danger">
                                <strong>Erreur</strong> Le nom est trop long !
                                </div>';
                            }
                        } else {
                            // Message d'erreur le prénom est trop long
                            $mess = '<div class="col-4 alert alert-danger">
                            <strong>Erreur</strong> Le prénom est trop long !
                            </div>';
                        }
                    } else {
                        // Message d'erreur le mail est trop long
                        $mess = '<div class="col-4 alert alert-danger">
                        <strong>Erreur</strong> L\'email est trop long !
                        </div>';
                    }
                } else {
                    // Message d'erreur l'utilisateur n'a pas été modifié
                    $mess = '<div class="col-4 alert alert-danger">
                    <strong>Erreur</strong> Veuillez remplir tous les champs !
                    </div>';
                }
            }

            // Vérifie que le formulaire a été soumis
            if (isset($_POST['editSubmitP'])) {

                // Vérifie que tous les champs sont remplis
                if (!empty($_POST['mdp']) && isset($_POST['mdp']) && !empty($_POST['newMdp']) && isset($_POST['newMdp'])) {

                    // Filtre les input de type poste pour enlever les caractères indésirables
                    $mdp = nettoyer(filter_input(INPUT_POST, 'mdp', FILTER_DEFAULT));
                    $newMdp = nettoyer(filter_input(INPUT_POST, 'newMdp', FILTER_DEFAULT));

                    $messMdp = UtilisateurManager::editPassword($idUtilisateur, $mdp, $newMdp);
                } else {

                    // Message d'erreur le mot de passe n'a pas été modifié
                    $messMdp = '<div class="col-4 alert alert-danger">
                    <strong>Erreur</strong> Veuillez remplir tous les champs !
                    </div>';
                }
            }
        }

        $view = ROOT . '/view/editUtilisateur.php';
        // appelle la vue
        $params = array();
        $params['unUtilisateur'] = $unUtilisateur;
        $params['mess'] = $mess;
        $params['messMdp'] = $messMdp;
        $params['exist'] = $exist;
        self::render($view, $params);
    }

    /**
     * Action qui affiche les utilisateurs
     * params : tableau des paramètres
     */
    public static function show($params)
    {

        $lesUtilisateurs = UtilisateurManager::getLesUtilisateurs();

        $view = ROOT . '/view/dashboard.php';
        // appelle la vue
        $params = array();
        $params['lesUtilisateurs'] = $lesUtilisateurs;
        self::render($view, $params);
    }

    /**
     * Action qui déconnecte l'utilisateur
     * params : tableau des paramètres
     */
    public static function deconnexion($params)
    {

        // appelle la vue
        $view = ROOT . '/view/deconnexion.php';
        $params = array();
        self::render($view, $params);
    }
}
