<?php

/**
 * /model/PosteManager.php
 *
 * Définition de la class PosteManager
 * Class qui gère les interactions entre les postes de l'application
 * et les postes de la bdd
 *
 * @author A. Espinoza
 * @date 03/2023
 */

class PosteManager
{

    private static ?\PDO $cnx = null;
    private static Poste $unPoste;
    private static array $lesPostes = array();

    /**
     * getLesPostes
     * récupère dans la bbd tous les postes
     *
     * @return array
     */
    public static function getLesPostes(): array
    {
        try {
            if (self::$cnx == null) {
                self::$cnx = DbManager::getConnexion();
            }

            // Requête select qui récupère toutes les informations des postes
            $sql = 'SELECT id, libelle, designation, desactiver, lienQuestion';
            $sql .= ' FROM poste';
            $stmt = self::$cnx->prepare($sql);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_OBJ);
            while ($row = $stmt->fetch()) {

                self::$unPoste = new Poste();
                self::$unPoste->setId($row->id);
                self::$unPoste->setLibelle($row->libelle);
                self::$unPoste->setDesignation($row->designation);
                self::$unPoste->setDesactiver($row->desactiver);
                self::$unPoste->setLienQuestion($row->lienQuestion);
                self::$lesPostes[] = self::$unPoste;
            }
            return self::$lesPostes;
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    /**
     * getLesPostesNonDesac
     * récupère dans la bbd tous les postes aqui ne sont pas désactiver
     *
     * @return array
     */
    public static function getLesPostesNonDesac(): array
    {
        try {
            if (self::$cnx == null) {
                self::$cnx = DbManager::getConnexion();
            }

            // Requête select qui récupère toutes les informations des postes qui ne sont pas fermés
            $sql = 'SELECT id, libelle, designation, desactiver, lienQuestion';
            $sql .= ' FROM poste';
            $sql .= ' WHERE desactiver = 0';
            $stmt = self::$cnx->prepare($sql);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_OBJ);
            while ($row = $stmt->fetch()) {

                self::$unPoste = new Poste();
                self::$unPoste->setId($row->id);
                self::$unPoste->setLibelle($row->libelle);
                self::$unPoste->setDesignation($row->designation);
                self::$unPoste->setDesactiver($row->desactiver);
                self::$unPoste->setLienQuestion($row->lienQuestion);
                self::$lesPostes[] = self::$unPoste;
            }
            return self::$lesPostes;
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    /**
     * getPosteById
     * récupère dans la bbd le poste
     * avec l'id passé en paramètre
     *
     * @param int
     * @return Poste
     */
    public static function getPosteById(int $idPoste): Poste
    {
        try {
            if (self::$cnx == null) {
                self::$cnx = DbManager::getConnexion();
            }

            // Requête select qui récupère toutes les informations du poste
            $sql = 'SELECT id, libelle, designation, desactiver, lienQuestion';
            $sql .= ' FROM poste';
            $sql .= ' WHERE id = :idPoste;';
            $stmt = self::$cnx->prepare($sql);
            $stmt->bindParam(':idPoste', $idPoste, PDO::PARAM_INT);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_OBJ);
            $row = $stmt->fetch();

            self::$unPoste = new Poste();
            self::$unPoste->setId($row->id);
            self::$unPoste->setLibelle($row->libelle);
            self::$unPoste->setDesignation($row->designation);
            self::$unPoste->setDesactiver($row->desactiver);
            self::$unPoste->setLienQuestion($row->lienQuestion);

            return self::$unPoste;
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    /**
     * deletePoste
     * supprime le poste avec l'id passé en paramètre
     *
     * @param int
     * @return void
     */
    public static function deletePoste(int $idPoste): void
    {
        try {
            if (self::$cnx == null) {
                self::$cnx = DbManager::getConnexion();
            }

            // Requête select qui récupère tous les id des candidats du poste
            $sql = 'SELECT idCandidat';
            $sql .= ' FROM Postuler';
            $sql .= ' WHERE idPoste = :idPoste';
            $stmt = self::$cnx->prepare($sql);
            $stmt->bindParam(':idPoste', $idPoste, PDO::PARAM_INT);
            $stmt->execute();

            // Requête delete qui supprime toutes les candidatures du poste
            $sql1 = 'DELETE FROM postuler';
            $sql1 .= ' WHERE idPoste = :idP;';
            $stmt1 = self::$cnx->prepare($sql1);
            $stmt1->bindParam(':idP', $idPoste, PDO::PARAM_INT);
            $stmt1->execute();

            while ($row = $stmt->fetch()) {

                $idC = $row['idCandidat'];

                // Requête delete qui supprime le candidat
                $sql = 'DELETE FROM candidat';
                $sql .= ' WHERE id = :idC';
                $stmt = self::$cnx->prepare($sql);
                $stmt->bindParam(':idC', $idC, PDO::PARAM_INT);
                $stmt->execute();
            }

            // Requête delete qui supprime le poste
            $sql = 'DELETE FROM poste';
            $sql .= ' WHERE id = :idP;';
            $stmt = self::$cnx->prepare($sql);
            $stmt->bindParam(':idP', $idPoste, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    /**
     * desactiverPoste
     * ferme le recrutement du poste avec l'id passé en paramètre
     *
     * @param int
     * @return void
     */
    public static function desactiverPoste(int $idPoste): void
    {
        try {
            if (self::$cnx == null) {
                self::$cnx = DbManager::getConnexion();
            }

            // Requête select qui récupère tous les id candidats des candidats du poste et de l'id statut 3
            $sql = 'SELECT idCandidat';
            $sql .= ' FROM postuler';
            $sql .= ' WHERE idPoste = :idPoste';
            $sql .= ' AND idCandidat IN (';
            $sql .= ' SELECT id';
            $sql .= ' FROM candidat';
            $sql .= ' WHERE idStatut = 3);';
            $stmt = self::$cnx->prepare($sql);
            $stmt->bindParam(':idPoste', $idPoste, PDO::PARAM_INT);
            $stmt->execute();

            // Requête delete qui supprime toutes les candidatures avec l'id statut 3 du poste
            $sql1 = 'DELETE FROM postuler';
            $sql1 .= ' WHERE idPoste = :idPoste';
            $sql1 .= ' AND idCandidat IN (';
            $sql1 .= ' SELECT id';
            $sql1 .= ' FROM candidat';
            $sql1 .= ' WHERE idStatut = 3);';
            $stmt1 = self::$cnx->prepare($sql1);
            $stmt1->bindParam(':idPoste', $idPoste, PDO::PARAM_INT);
            $stmt1->execute();

            while ($row = $stmt->fetch()) {

                $idC = $row['idCandidat'];

                // Requête delete qui supprime le candidat
                $sql = 'DELETE FROM candidat';
                $sql .= ' WHERE id = :idC;';
                $stmt = self::$cnx->prepare($sql);
                $stmt->bindParam(':idC', $idC, PDO::PARAM_INT);
                $stmt->execute();
            }

            // Requête update qui ferme le recrutement du poste
            $sql = 'UPDATE poste';
            $sql .= ' SET desactiver = 1';
            $sql .= ' WHERE id = :idP;';
            $stmt = self::$cnx->prepare($sql);
            $stmt->bindParam(':idP', $idPoste, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    /**
     * reactiverPoste
     * reouvre le recrutement du poste avec l'id passé en paramètre
     *
     * @param int
     * @return void
     */
    public static function reactiverPoste(int $idPoste): void
    {
        try {
            if (self::$cnx == null) {
                self::$cnx = DbManager::getConnexion();
            }

            // Requête update qui réouvre le recrutement du poste
            $sql = 'UPDATE poste';
            $sql .= ' SET desactiver = 0';
            $sql .= ' WHERE id = :idP;';
            $stmt = self::$cnx->prepare($sql);
            $stmt->bindParam(':idP', $idPoste, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    /**
     * addPoste
     * ajoute le poste avec les valeurs saisies en paramètre
     *
     * @param string
     * @param string
     * @param string
     * @return void
     */
    public static function addPoste(string $libelle, string $designation, string $lienQuestion): void
    {
        try {
            if (self::$cnx == null) {
                self::$cnx = DbManager::getConnexion();
            }

            // Requête insert qui insère un nouveau poste
            $sql = 'INSERT INTO `poste` (`libelle`, `designation`, `lienQuestion`) VALUES';
            $sql .= ' (:libelle, :designation, :lienQuestion);';
            $stmt = self::$cnx->prepare($sql);
            $stmt->bindParam(':libelle', $libelle, PDO::PARAM_STR);
            $stmt->bindParam(':designation', $designation, PDO::PARAM_STR);
            $stmt->bindParam(':lienQuestion', $lienQuestion, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    /**
     * editPoste
     * modifie le poste avec les valeurs saisies en paramètre
     *
     * @param int
     * @param string
     * @param string
     * @param string
     * @return void
     */
    public static function editPoste(int $idPoste, string $libelle, string $designation, string $lienQuestion): void
    {
        try {
            if (self::$cnx == null) {
                self::$cnx = DbManager::getConnexion();
            }

            // Requête update qui modifie les valeurs du poste
            $sql = 'UPDATE poste SET libelle = :libelle, designation = :designation, lienQuestion = :lienQuestion';
            $sql .= ' WHERE id = :idPoste';
            $stmt = self::$cnx->prepare($sql);
            $stmt->bindParam(':libelle', $libelle, PDO::PARAM_STR);
            $stmt->bindParam(':designation', $designation, PDO::PARAM_STR);
            $stmt->bindParam(':lienQuestion', $lienQuestion, PDO::PARAM_STR);
            $stmt->bindParam(':idPoste', $idPoste, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    /**
     * existPoste
     * vérifie si le poste existe
     *
     * @param int
     * @return bool
     */
    public static function existPoste(int $idPoste): bool
    {
        try {
            if (self::$cnx == null) {
                self::$cnx = DbManager::getConnexion();
            }
            $exist = false;

            // Requête select qui récupère toutes les informations du poste
            $sql = 'SELECT id, libelle, designation FROM poste';
            $sql .= ' WHERE id = :idP';
            $stmt = self::$cnx->prepare($sql);
            $stmt->bindParam(':idP', $idPoste, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->rowCount();

            // Le poste existe
            if ($row == 1) {
                $exist = true;
            }

            return $exist;
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }
}
