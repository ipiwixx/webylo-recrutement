<?php

/**
 * /model/Candidat.php
 * Définition de la class Candidat
 *
 * @author A. Espinoza
 * @date 03/2023
 */

class Candidat
{

    /*
     * Attributs
     */
    private int $id;
    private int $idPoste;
    private int $idStatut;
    private string $nom;
    private string $prenom;
    private string $email;
    private string $ville;
    private string $adresse;
    private string $desiPoste;
    private string $libStatut;
    private ?string $fichiersComplementaires;
    private ?string $cv;
    private int $tel;
    private int $cp;
    private int $note;
    private DateTime $dateNaissance;
    private DateTime $dateInscription;

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
    public function getVille(): string
    {
        return $this->ville;
    }
    public function setVille(string $ville)
    {
        $this->ville = $ville;
    }
    public function getAdresse(): string
    {
        return $this->adresse;
    }
    public function setAdresse(string $adresse)
    {
        $this->adresse = $adresse;
    }
    public function getNumTel(): int
    {
        return $this->tel;
    }
    public function setNumTel(int $tel)
    {
        $this->tel = $tel;
    }
    public function getCp(): int
    {
        return $this->cp;
    }
    public function setCp(int $cp)
    {
        $this->cp = $cp;
    }
    public function getNote(): ?int
    {
        return $this->note;
    }
    public function setNote(?int $note)
    {
        $this->note = $note;
    }
    public function getDateNaissance(): DateTime
    {
        return $this->dateNaissance;
    }
    public function setDateNaissance(DateTime $dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;
    }
    public function getDateInscription(): DateTime
    {
        return $this->dateInscription;
    }
    public function setDateInscription(DateTime $dateInscription)
    {
        $this->dateInscription = $dateInscription;
    }
    public function getCv(): ?string
    {
        return $this->cv;
    }
    public function setCv(?string $cv)
    {
        $this->cv = $cv;
    }
    public function getFichiersComplementaires(): ?string
    {
        return $this->fichiersComplementaires;
    }
    public function setFichiersComplementaires(?string $fichiersComplementaires)
    {
        $this->fichiersComplementaires = $fichiersComplementaires;
    }
    public function getDesiPoste(): string
    {
        return $this->desiPoste;
    }
    public function setDesiPoste(string $desiPoste)
    {
        $this->desiPoste = $desiPoste;
    }
    public function getLibStatut(): string
    {
        return $this->libStatut;
    }
    public function setLibStatut(string $libStatut)
    {
        $this->libStatut = $libStatut;
    }
    public function getIdPoste(): int
    {
        return $this->idPoste;
    }
    public function setIdPoste(int $idPoste)
    {
        $this->idPoste = $idPoste;
    }
    public function getIdStatut(): int
    {
        return $this->idStatut;
    }
    public function setIdStatut(int $idStatut)
    {
        $this->idStatut = $idStatut;
    }
}
