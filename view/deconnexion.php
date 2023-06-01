<?php

/**
 * /view/deconnexion.php
 *
 * Page de déconnexion
 *
 * @author A. Espinoza
 * @date 03/2023
 */

if (!isset($_SESSION['user'])) {
    header('Location: /erreur/');
} else {
    session_destroy();
    header('Location: /connexion/');
}
