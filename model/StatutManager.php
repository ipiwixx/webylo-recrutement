<?php

/**
 * /model/StatutManager.php
 *
 * Définition de la class StatutManager
 * Class qui gère les interactions entre les statuts de l'application
 * et les statuts de la bdd
 *
 * @author A. Espinoza
 * @date 03/2023
 */

class StatutManager
{

    private static ?\PDO $cnx = null;
    private static Statut $unStatut;
    private static array $lesStatuts = array();

    /**
     * getLesStatuts
     * récupère dans la bbd tous les statuts
     *
     * @return array
     */
    public static function getLesStatuts(): array
    {
        try {
            if (self::$cnx == null) {
                self::$cnx = DbManager::getConnexion();
            }

            // Requête select qui récupère toutes les informations des statuts
            $sql = 'SELECT id, libelle';
            $sql .= ' FROM statut';
            $stmt = self::$cnx->prepare($sql);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_OBJ);
            while ($row = $stmt->fetch()) {

                self::$unStatut = new Statut();
                self::$unStatut->setId($row->id);
                self::$unStatut->setLibelle($row->libelle);
                self::$lesStatuts[] = self::$unStatut;
            }
            return self::$lesStatuts;
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    /**
     * getStatutById
     * récupère dans la bbd le statut
     * avec l'id passé en paramètre
     *
     * @param int
     * @return Statut
     */
    public static function getStatutById(int $idStatut): Statut
    {
        try {
            if (self::$cnx == null) {
                self::$cnx = DbManager::getConnexion();
            }

            // Requête select qui récupère toutes les informations du statut
            $sql = 'SELECT id, libelle';
            $sql .= ' FROM statut';
            $sql .= ' WHERE id = :idStatut;';
            $stmt = self::$cnx->prepare($sql);
            $stmt->bindParam(':idStatut', $idStatut, PDO::PARAM_INT);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_OBJ);
            $row = $stmt->fetch();

            self::$unStatut = new Statut();
            self::$unStatut->setId($row->id);
            self::$unStatut->setLibelle($row->libelle);

            return self::$unStatut;
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    /**
     * deleteStatut
     * supprime le statut avec l'id passé en paramètre
     *
     * @param int
     * @return void
     */
    public static function deleteStatut(int $idStatut): void
    {
        try {
            if (self::$cnx == null) {
                self::$cnx = DbManager::getConnexion();
            }

            // Requête delete qui supprime tous les candidats du statut
            $sql = 'DELETE FROM candidat';
            $sql .= ' WHERE idStatut = :idS;';
            $stmt = self::$cnx->prepare($sql);
            $stmt->bindParam(':idS', $idStatut, PDO::PARAM_INT);
            $stmt->execute();

            // Requête delete qui supprime le statut
            $sql = 'DELETE FROM statut';
            $sql .= ' WHERE id = :idS;';
            $stmt = self::$cnx->prepare($sql);
            $stmt->bindParam(':idS', $idStatut, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    /**
     * addStatut
     * ajoute le statut avec les valeurs saisies en paramètre
     *
     * @param string
     * @return void
     */
    public static function addStatut(string $libelle): void
    {
        try {
            if (self::$cnx == null) {
                self::$cnx = DbManager::getConnexion();
            }

            // Requête insert qui insère un nouveau statut
            $sql = 'INSERT INTO `statut` (`libelle`) VALUES';
            $sql .= ' (:libelle);';
            $stmt = self::$cnx->prepare($sql);
            $stmt->bindParam(':libelle', $libelle, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    /**
     * editStatut
     * modifie le statut avec les valeurs saisies en paramètre
     *
     * @param int
     * @param string
     * @return void
     */
    public static function editStatut(int $idStatut, string $libelle): void
    {
        try {
            if (self::$cnx == null) {
                self::$cnx = DbManager::getConnexion();
            }

            // Requête update qui modifie les valeurs du statut
            $sql = 'UPDATE statut SET libelle = :libelle';
            $sql .= ' WHERE id = :idStatut';
            $stmt = self::$cnx->prepare($sql);
            $stmt->bindParam(':libelle', $libelle, PDO::PARAM_STR);
            $stmt->bindParam(':idStatut', $idStatut, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    /**
     * existStatut
     * vérifie si le statut existe
     *
     * @param int
     * @return bool
     */
    public static function existStatut(int $idStatut): bool
    {
        try {
            if (self::$cnx == null) {
                self::$cnx = DbManager::getConnexion();
            }
            $exist = false;

            // Requête select qui récupère toutes les informations du statut
            $sql = 'SELECT id, libelle FROM statut';
            $sql .= ' WHERE id = :idS';
            $stmt = self::$cnx->prepare($sql);
            $stmt->bindParam(':idS', $idStatut, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->rowCount();

            // Le statut existe
            if ($row == 1) {
                $exist = true;
            }

            return $exist;
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }
}
