<?php

/**
 * /model/Poste.php
 * Définition de la class Poste
 *
 * @author A. Espinoza
 * @date 03/2023
 */

class Poste
{

    /*
     * Attributs
     */
    private int $id;
    private string $libelle;
    private string $designation;
    private ?string $lienQuestion;
    private bool $desactiver;

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
    public function getDesignation(): string
    {
        return $this->designation;
    }
    public function setDesignation(string $designation)
    {
        $this->designation = $designation;
    }
    public function getDesactiver(): bool
    {
        return $this->desactiver;
    }
    public function setDesactiver(bool $desactiver)
    {
        $this->desactiver = $desactiver;
    }
    public function getLienQuestion(): ?string
    {
        return $this->lienQuestion;
    }
    public function setLienQuestion(?string $lienQuestion)
    {
        $this->lienQuestion = $lienQuestion;
    }
}
