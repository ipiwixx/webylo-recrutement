<?php

/**
 * /controller/StatutController.php
 *
 * Contrôleur pour l'entité Statut
 *
 * @author A. Espinoza
 * @date 03/2023
 */

class StatutController extends Controller
{

    /**
     * Action qui ajoute un statut
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
                if (!empty($_POST['libelle']) && isset($_POST['libelle'])) {

                    // Filtre les input de type poste pour enlever les caractères indésirables
                    $libelle = nettoyer(filter_input(INPUT_POST, 'libelle', FILTER_DEFAULT));

                    if (strlen($libelle) <= 64) { // Vérifie que la longueur du libelle soit inférieur ou égal à 64

                        StatutManager::addStatut($libelle);

                        // Message de succès le statut a été ajouté
                        $mess = '<div class="col-4 alert alert-success">
                        <strong>Succès</strong> Le statut a été ajouté !
                        </div>';
                    } else {
                        // Message d'erreur le libelle est trop long
                        $mess = '<div class="col-4 alert alert-danger">
                        <strong>Erreur</strong> Le libelle est trop long !
                        </div>';
                    }
                } else {
                    // Message d'erreur le statut n'a pas été ajouté
                    $mess = '<div class="col-4 alert alert-danger">
                    <strong>Erreur</strong> Veuillez remplir tous les champs !
                    </div>';
                }
            }
        }

        $view = ROOT . '/view/addStatut.php';
        // appelle la vue
        $params = array();
        $params['mess'] = $mess;
        self::render($view, $params);
    }

    /**
     * Action qui supprime un statut
     * params : tableau des paramètres
     */
    public static function delete($params)
    {
        $mess = '';

        /*// Vérifie que l'utilisateur est connecté
            if(isset($_SESSION['user'])) { 

                // Vérifie qu'il y a bien un id statut dans l'url
                if(isset($_GET['idS'])) {
                
                // Filtre les variables GET pour enlever les caractères indésirables
                $idStatut = nettoyer(filter_var($_GET['idS'], FILTER_VALIDATE_INT));

                StatutManager::deleteStatut($idStatut);

                // Message de succès le statut a été supprimé
                $mess = '<div class="col-4 alert alert-success">
                <strong>Succès</strong> Le statut a été supprimé !
                </div>';
            }   
        }*/

        $view = ROOT . '/view/dashboard.php';
        // appelle la vue
        $params = array();
        $params['mess'] = $mess;
        self::render($view, $params);
    }

    /**
     * Action qui moddifie un statut
     * params : tableau des paramètres
     */
    public static function edit($params)
    {

        // Vérifie que l'utilisateur est connecté
        if (isset($_SESSION['user'])) {

            // Vérifie qu'il y a bien un id statut dans l'url
            if (isset($_GET['idS'])) {

                // Filtre les variables GET pour enlever les caractères indésirables
                $idStatut = nettoyer(filter_var($_GET['idS'], FILTER_VALIDATE_INT));

                $exist = StatutManager::existStatut($idStatut);

                if ($exist == true) {
                    $unStatut = StatutManager::getStatutById($idStatut);
                } else {
                    $unStatut = null;
                }
            }
            $mess = '';

            // Vérifie que le formulaire a été soumis
            if (isset($_POST['editSubmit'])) {

                // Vérifie que tous les champs sont remplis
                if (!empty($_POST['libelle']) && isset($_POST['libelle']) && isset($_GET['idS'])) {

                    // Filtre les input de type poste pour enlever les caractères indésirables
                    $libelle = nettoyer(filter_input(INPUT_POST, 'libelle', FILTER_DEFAULT));
                    $idStatut = nettoyer(filter_var($_GET['idS'], FILTER_VALIDATE_INT));

                    if (strlen($libelle) <= 64) { // Vérifie que la longueur du libelle soit inférieur ou égal à 64

                        StatutManager::editStatut($idStatut, $libelle);

                        // Message de succès le statut a été modifié
                        $mess = '<div class="col-4 alert alert-success">
                        <strong>Succès</strong> Le statut a été modifé !
                        </div>';
                    } else {
                        // Message d'erreur le libelle est trop long
                        $mess = '<div class="col-4 alert alert-danger">
                        <strong>Erreur</strong> Le libelle est trop long !
                        </div>';
                    }
                } else {
                    // Message d'erreur le statut n'a pas été modifié
                    $mess = '<div class="col-4 alert alert-danger">
                    <strong>Erreur</strong> Veuillez remplir tous les champs !
                    </div>';
                }
            }
        }

        $view = ROOT . '/view/editStatut.php';
        // appelle la vue
        $params = array();
        $params['unStatut'] = $unStatut;
        $params['mess'] = $mess;
        $params['exist'] = $exist;
        self::render($view, $params);
    }

    /**
     * Action qui affiche les statuts
     * params : tableau des paramètres
     */
    public static function show($params)
    {

        $lesStatuts = StatutManager::getLesStatuts();

        $view = ROOT . '/view/dashboard.php';
        // appelle la vue
        $params = array();
        $params['lesStatuts'] = $lesStatuts;
        self::render($view, $params);
    }
}
