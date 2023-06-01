<?php

/**
* /autoload.php
* Charge les class
*
*
* @author A. Espinoza
* @date 03/2023
*/

    require_once ROOT.'/model/DbManager.php';
    require_once ROOT.'/model/utils-form.php';
    require_once ROOT.'/model/Candidat.php';
    require_once ROOT.'/model/CandidatManager.php';
    require_once ROOT.'/model/Poste.php';
    require_once ROOT.'/model/PosteManager.php';
    require_once ROOT.'/model/Statut.php';
    require_once ROOT.'/model/StatutManager.php';
    require_once ROOT.'/model/Utilisateur.php';
    require_once ROOT.'/model/UtilisateurManager.php';

    require_once ROOT.'/controller/Controller.php';
