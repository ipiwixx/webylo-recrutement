<?php

/**
 * /controller/ViewController.php
 *
 * Contrôleur pour les pages de vue uniquement
 *
 * @author A. Espinoza
 * @date 03/2023
 */

class ViewController extends Controller
{

    /**
     * Action qui affiche la page des mentions légales
     * params : tableau des paramètres
     */
    public static function mentions($params)
    {

        // appelle la vue
        $view = ROOT . '/view/mentions.php';
        $params = array();
        self::render($view, $params);
    }

    /**
     * Action qui affiche la page de l'utilisation des cookies
     * params : tableau des paramètres
     */
    public static function cookies($params)
    {

        // appelle la vue
        $view = ROOT . '/view/cookies.php';
        $params = array();
        self::render($view, $params);
    }

    /**
     * Action qui affiche la page de la politique de confidentialité
     * params : tableau des paramètres
     */
    public static function politique($params)
    {

        // appelle la vue
        $view = ROOT . '/view/politique.php';
        $params = array();
        self::render($view, $params);
    }

    /**
     * Action qui affiche la page d'erreur
     * params : tableau des paramètres
     */
    public static function erreur($params)
    {

        // appelle la vue
        $view = ROOT . '/view/error.php';
        $params = array();
        self::render($view, $params);
    }
}
