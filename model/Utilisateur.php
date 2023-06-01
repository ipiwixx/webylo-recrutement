<?php

/**
 * /model/Utilisateur.php
 * Définition de la class Utilisateur
 *
 * @author A. Espinoza
 * @date 03/2023
 */

class Utilisateur
{

    /*
     * Attributs
     */
    private int $id;
    private string $nom;
    private string $prenom;
    private string $email;
    private string $mdp;
    private string $role;

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
    public function getNom(): string
    {
        return $this->nom;
    }
    public function setNom(string $nom)
    {
        $this->nom = $nom;
    }
    public function getPrenom(): string
    {
        return $this->prenom;
    }
    public function setPrenom(string $prenom)
    {
        $this->prenom = $prenom;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function setEmail(string $email)
    {
        $this->email = $email;
    }
    public function getMdp(): string
    {
        return $this->mdp;
    }
    public function setMdp(string $mdp)
    {
        $this->mdp = $mdp;
    }
    public function getRole(): string
    {
        return $this->role;
    }
    public function setRole(string $role)
    {
        $this->role = $role;
    }
}
