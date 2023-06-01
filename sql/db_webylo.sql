-- Base de données pour le site Webylo
-- SGBD MariaDB
-- Script de création ou de restauration
-- 2SIO v2023 Antoine Espinoza

-- Création de la base si elle n'existe pas
CREATE DATABASE IF NOT EXISTS `db_webylo` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

CREATE USER 'AdminWebylo'@'127.0.0.1' IDENTIFIED BY 'W3byl0Adm1n*';

USE `db_webylo`;

-- Suppression des tables si elles existent
DROP TABLE IF EXISTS `poste`;
DROP TABLE IF EXISTS `statut`;
DROP TABLE IF EXISTS `candidat`;
DROP TABLE IF EXISTS `postuler`;
DROP TABLE IF EXISTS `utilisateur`;
DROP TABLE IF EXISTS `recuperation`;
DROP TABLE IF EXISTS `logins`;

--
-- Création des tables
-- 
CREATE TABLE IF NOT EXISTS `poste` (
	`id` integer NOT NULL AUTO_INCREMENT,
	`libelle` varchar(128),
	`designation` varchar(32),
	`desactiver` boolean DEFAULT 0,
	`lienQuestion` varchar(128),
	CONSTRAINT `pk_poste` PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `statut` (
	`id` integer NOT NULL AUTO_INCREMENT,
	`libelle` varchar(64),
	CONSTRAINT `pk_statut` PRIMARY KEY (`id`)
)ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `candidat` (
	`id` integer NOT NULL AUTO_INCREMENT,
	`nom` varchar(64),
	`prenom` varchar(64),
	`email` varchar(128),
	`numTel` char(10),
	`cp` char(5),
	`ville` varchar(64),
	`adresse` varchar(128),
	`dateNaissance` date,
	`dateInscription` date,
	`note` integer,
    `cv` varchar(128),
	`fichiersComplementaires` varchar(1024),
	`idStatut` integer,
	CONSTRAINT `pk_candidat` PRIMARY KEY (`id`),
	CONSTRAINT `fk_candidat_statut` FOREIGN KEY (idStatut) REFERENCES statut(id)
)ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `postuler` (
	`idCandidat` integer,
	`idPoste` integer,	
	CONSTRAINT `pk_postuler` PRIMARY KEY (`idCandidat`, `idPoste`),
	CONSTRAINT `fk_postuler_candidat` FOREIGN KEY (idCandidat) REFERENCES candidat(id),
	CONSTRAINT `fk_postuler_poste` FOREIGN KEY (idPoste) REFERENCES poste(id)
)ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `utilisateur` (
	`id` integer NOT NULL AUTO_INCREMENT,
	`nom` varchar(64),
	`prenom` varchar(64),
	`email` varchar(128),
	`mdp` varchar(128),
	`roles` varchar(64),
	CONSTRAINT `pk_utilisateur` PRIMARY KEY (`id`)
)ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `recuperation` (
	`id` integer NOT NULL AUTO_INCREMENT,
	`email` varchar(128),	
	`code` integer,	
	`confirme` integer default 0,
	CONSTRAINT `pk_recuperation` PRIMARY KEY (`id`)
)ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `logins` (
	`id` integer NOT NULL AUTO_INCREMENT,
	`created_at` datetime,	
	`email` varchar(128),
	`ip` varchar(32),	
	CONSTRAINT `pk_logins` PRIMARY KEY (`id`)
)ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------
--
-- Privilège utilisateur
--
GRANT SELECT, INSERT, UPDATE, DELETE ON db_webylo.candidat TO 'AdminWebylo'@'127.0.0.1';
GRANT SELECT, INSERT, UPDATE, DELETE ON db_webylo.utilisateur TO 'AdminWebylo'@'127.0.0.1';
GRANT SELECT, INSERT, UPDATE, DELETE ON db_webylo.poste TO 'AdminWebylo'@'127.0.0.1';
GRANT SELECT, INSERT, UPDATE, DELETE ON db_webylo.statut TO 'AdminWebylo'@'127.0.0.1';
GRANT SELECT, INSERT, UPDATE, DELETE ON db_webylo.postuler TO 'AdminWebylo'@'127.0.0.1';
GRANT SELECT, INSERT, UPDATE, DELETE ON db_webylo.recuperation TO 'AdminWebylo'@'127.0.0.1';
GRANT SELECT, INSERT, UPDATE, DELETE ON db_webylo.logins TO 'AdminWebylo'@'127.0.0.1';

-- 
-- Insertion des enregistrements
--
INSERT INTO `statut` (`libelle`) VALUES 
('En attente'),
('Accepté'),
('Refusé');

INSERT INTO `utilisateur` (`nom`, `prenom`, `email`, `mdp`, `roles`) VALUES 
('MAMPOUYA', 'Christian', 'contact@webylo.fr', '$2y$12$FuoKD74kyU3MvppMTqmkZO1V3YSMGeGMEYCWUQH8r9vFHh/oNJ7G.', 'admin'),
('IRLA', 'Laura', 'laura@webylo.fr', '$2y$12$FuoKD74kyU3MvppMTqmkZO1V3YSMGeGMEYCWUQH8r9vFHh/oNJ7G.', 'admin');;
-- les 2 mdp : Jarget130

INSERT INTO `poste` (`libelle`, `designation`, `lienQuestion`) VALUES 
('Spécialiste en Référencement', 'SEO', 'https://forms.gle/SKfBxtuBN91xF6KR8'),
('Community Manager', 'CM', 'https://forms.gle/etX3ZTD2ithTqt3w8');



