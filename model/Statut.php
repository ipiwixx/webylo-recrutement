<?php

/**
 * /model/Statut.php
 * Définition de la class Statut
 *
 * @author A. Espinoza
 * @date 03/2023
 */

class Statut
{

    /*
     * Attributs
     */
    private int $id;
    private string $libelle;

    /*
     * Constructeur
     */
    public function __construct()
    {
    }

    /*
     * Accesseurs
     */
    public function getId(): int
    {
        return $this->id;
    }
    public function setId(int $id)
    {
        $this->id = $id;
    }
    public function getLibelle(): string
    {
        return $this->libelle;
    }
    public function setLibelle(string $libelle)
    {
        $this->libelle = $libelle;
    }
}
