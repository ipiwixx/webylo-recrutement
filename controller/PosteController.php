<?php

/**
 * /controller/PosteController.php
 *
 * Contrôleur pour l'entité Poste
 *
 * @author A. Espinoza
 * @date 03/2023
 */

class PosteController extends Controller
{

    /**
     * Action qui ajoute un poste
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
                if (!empty($_POST['libelle']) && isset($_POST['libelle']) && !empty($_POST['designation']) && isset($_POST['designation'])) {

                    // Filtre les input de type poste pour enlever les caractères indésirables
                    $libelle = nettoyer(filter_input(INPUT_POST, 'libelle', FILTER_DEFAULT));
                    $designation = nettoyer(filter_input(INPUT_POST, 'designation', FILTER_DEFAULT));
                    $lienQuestion = nettoyer(filter_input(INPUT_POST, 'lienQuestion', FILTER_DEFAULT));

                    if (strlen($libelle) <= 128) { // Vérifie que la longueur du libelle soit inférieur ou égal à 128
                        if (strlen($designation) <= 32) { // Vérifie que la longueur de la désignation soit inférieur ou égal à 32
                            if (strlen($lienQuestion) <= 128) { // Vérifie que la longueur du lien du questionnaire soit inférieur ou égal à 128

                                PosteManager::addPoste($libelle, $designation, $lienQuestion);

                                // Message de succès le poste a été ajouté
                                $mess = '<div class="col-4 alert alert-success">
                                <strong>Succès</strong> Le poste a été ajouté !
                                </div>';
                            } else {
                                // Message d'erreur le lien du questionnaire est trop long
                                $mess = '<div class="col-4 alert alert-danger">
                                <strong>Erreur</strong> Le lien du questionnaire est trop long !
                                </div>';
                            }
                        } else {
                            // Message d'erreur la désignation est trop longue
                            $mess = '<div class="col-4 alert alert-danger">
                            <strong>Erreur</strong> La désignation est trop longue !
                            </div>';
                        }
                    } else {
                        // Message d'erreur le libelle est trop long
                        $mess = '<div class="col-4 alert alert-danger">
                        <strong>Erreur</strong> Le libelle est trop long !
                        </div>';
                    }
                } else {
                    // Message d'erreur le poste n'a pas été ajouté
                    $mess = '<div class="col-4 alert alert-danger">
                    <strong>Erreur</strong> Veuillez remplir tous les champs !
                    </div>';
                }
            }
        }

        $view = ROOT . '/view/addPoste.php';
        // appelle la vue
        $params = array();
        $params['mess'] = $mess;
        self::render($view, $params);
    }

    /**
     * Action qui supprime un poste
     * params : tableau des paramètres
     */
    public static function delete($params)
    {

        // Vérifie que l'utilisateur est connecté
        if (isset($_SESSION['user'])) {

            // Vérifie qu'il y a bien un id poste dans l'url
            if (isset($_GET['idP'])) {

                // Filtre les variables GET pour enlever les caractères indésirables
                $idPoste = nettoyer(filter_var($_GET['idP'], FILTER_VALIDATE_INT));

                PosteManager::deletePoste($idPoste);
            }
        }

        $view = ROOT . '/view/dashboard.php';
        // appelle la vue
        $params = array();
        self::render($view, $params);
    }

    /**
     * Action qui moddifie un poste
     * params : tableau des paramètres
     */
    public static function edit($params)
    {

        // Vérifie que l'utilisateur est connecté
        if (isset($_SESSION['user'])) {

            // Vérifie qu'il y a bien un id poste dans l'url
            if (isset($_GET['idP'])) {

                // Filtre les variables GET pour enlever les caractères indésirables
                $idPoste = nettoyer(filter_var($_GET['idP'], FILTER_VALIDATE_INT));

                $exist = PosteManager::existPoste($idPoste);

                if ($exist == true) {
                    $unPoste = PosteManager::getPosteById($idPoste);
                } else {
                    $unPoste = null;
                }
            }
            $mess = '';

            // Vérifie que le formulaire a été soumis
            if (isset($_POST['editSubmit'])) {

                // Vérifie que tous les champs sont remplis
                if (!empty($_POST['libelle']) && isset($_POST['libelle']) && !empty($_POST['designation']) && isset($_POST['designation']) && isset($_GET['idP'])) {

                    // Filtre les input de type poste pour enlever les caractères indésirables
                    $libelle = nettoyer(filter_input(INPUT_POST, 'libelle', FILTER_DEFAULT));
                    $designation = nettoyer(filter_input(INPUT_POST, 'designation', FILTER_DEFAULT));
                    $lienQuestion = nettoyer(filter_input(INPUT_POST, 'lienQuestion', FILTER_DEFAULT));
                    $idPoste = nettoyer(filter_var($_GET['idP'], FILTER_VALIDATE_INT));

                    if (strlen($libelle) <= 128) { // Vérifie que la longueur du libelle soit inférieur ou égal à 128
                        if (strlen($designation) <= 32) { // Vérifie que la longueur de la désignation soit inférieur ou égal à 32
                            if (strlen($lienQuestion) <= 128) { // Vérifie que la longueur du lien du questionnaire soit inférieur ou égal à 128

                                PosteManager::editPoste($idPoste, $libelle, $designation, $lienQuestion);

                                // Message de succès le poste a été modifié
                                $mess = '<div class="col-4 alert alert-success">
                                <strong>Succès</strong> Le poste a été modifé !
                                </div>';
                            } else {
                                // Message d'erreur le lien du questionnaire est trop long
                                $mess = '<div class="col-4 alert alert-danger">
                                <strong>Erreur</strong> Le lien du questionnaire est trop long !
                                </div>';
                            }
                        } else {
                            // Message d'erreur la désignation est trop longue
                            $mess = '<div class="col-4 alert alert-danger">
                            <strong>Erreur</strong> La désignation est trop longue !
                            </div>';
                        }
                    } else {
                        // Message d'erreur le libelle est trop long
                        $mess = '<div class="col-4 alert alert-danger">
                        <strong>Erreur</strong> Le libelle est trop long !
                        </div>';
                    }
                } else {
                    // Message d'erreur le poste n'a pas été modifié
                    $mess = '<div class="col-4 alert alert-danger">
                    <strong>Erreur</strong> Veuillez remplir tous les champs !
                    </div>';
                }
            }
        }

        $view = ROOT . '/view/editPoste.php';
        // appelle la vue
        $params = array();
        $params['unPoste'] = $unPoste;
        $params['mess'] = $mess;
        $params['exist'] = $exist;
        self::render($view, $params);
    }

    /**
     * Action qui affiche les postes
     * params : tableau des paramètres
     */
    public static function show($params)
    {

        $lesPostes = PosteManager::getLesPostes();

        $view = ROOT . '/view/dashboard.php';
        // appelle la vue
        $params = array();
        $params['lesPostes'] = $lesPostes;
        self::render($view, $params);
    }

    /**
     * Action qui désactive un poste
     * params : tableau des paramètres
     */
    public static function desactiver($params)
    {

        // Vérifie que l'utilisateur est connecté
        if (isset($_SESSION['user'])) {

            // Vérifie qu'il y a bien un id utilisateur dans l'url
            if (isset($_GET['idP'])) {

                // Filtre les variables GET pour enlever les caractères indésirables
                $idPoste = nettoyer(filter_var($_GET['idP'], FILTER_VALIDATE_INT));

                PosteManager::desactiverPoste($idPoste);
            }
        }

        $view = ROOT . '/view/dashboard.php';
        // appelle la vue
        $params = array();
        self::render($view, $params);
    }

    /**
     * Action qui reactive un poste
     * params : tableau des paramètres
     */
    public static function reactiver($params)
    {

        // Vérifie que l'utilisateur est connecté
        if (isset($_SESSION['user'])) {

            // Vérifie qu'il y a bien un id poste dans l'url
            if (isset($_GET['idP'])) {

                // Filtre les variables GET pour enlever les caractères indésirables
                $idPoste = nettoyer(filter_var($_GET['idP'], FILTER_VALIDATE_INT));

                PosteManager::reactiverPoste($idPoste);
            }
        }

        $view = ROOT . '/view/dashboard.php';
        // appelle la vue
        $params = array();
        self::render($view, $params);
    }
}
