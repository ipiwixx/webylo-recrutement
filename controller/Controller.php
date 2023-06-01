<?php

/**
 * /controller/Controller.php
 *
 * class technique pour définir les membres communs aux controllers
 *
 * @author A. Espinoza
 * @date 03/2023
 */

class Controller
{
    public static function render($view, $params)
    {
        extract($params);
        require_once $view;
    }
}
