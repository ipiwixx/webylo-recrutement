<?php

/**
 * /model/DbManager.php
 *
 * Définition de la class DbManager
 * Class qui implémente toutes les fonctions d'accès à la base de données
 *
 * @author A. Espinoza
 * @date 03/2023
 */

// attributs techniques d'accès à la bdd
const HOST = '127.0.0.1'; // adresse IP de l'hôte
const PORT = '3307'; // 3306 ou 3307:MariaDB / 3308: MySQL
const DBNAME = 'db_webylo'; // nom de la bdd
const CHARSET = 'utf8';
const LOGIN = 'AdminWebylo'; // login pour la connexion
const MDP = 'W3byl0Adm1n*';  // password pour la connexion

class DbManager
{

    private static ?\PDO $cnx = null;

    /**
     * getConnexion
     * établit la connexion à la base de données
     *
     * @return void
     */
    public static function getConnexion()
    {
        if (self::$cnx == null) {
            try {
                $dsn = 'mysql:host=' . HOST . ';port=' . PORT . ';dbname=' . DBNAME . ';charset=' . CHARSET;
                self::$cnx = new PDO($dsn, LOGIN, MDP);
                self::$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die('Erreur : ' . $e->getMessage());
            }
        }
        return self::$cnx;
    }
}
