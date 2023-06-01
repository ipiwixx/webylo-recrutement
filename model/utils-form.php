<?php


/**
 * /model/utils-form.php
 *
 * Utilitaires pour formulaires
 *
 * @author A. Espinoza
 * @date 03/2022
 */

/**
 * nettoyer
 * Nettoie une donnée reçue d'un formulaire
 * @param string $data
 * @return string $data
 */

function nettoyer($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
