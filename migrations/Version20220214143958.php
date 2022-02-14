<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220214143958 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `table` DROP type');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE nom nom VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE prenom prenom VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE telephone telephone VARCHAR(8) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE mot_de_passe mot_de_passe VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE cin cin VARCHAR(8) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE article CHANGE title title VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE contenu contenu LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE banner banner VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE blog_client CHANGE title title VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE contenu contenu LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE statut statut VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE carte_bancaire CHANGE numero numero VARCHAR(16) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE nom nom VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE cvv cvv VARCHAR(3) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE client CHANGE telephone telephone VARCHAR(8) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE mot_de_passe mot_de_passe VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE nom nom VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE prenom prenom VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE commande CHANGE statut statut VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE methode methode VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE statut_paiement statut_paiement VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE commentaire CHANGE contenu contenu LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE element_details CHANGE options options LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE employe CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE nom nom VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE prenom prenom VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE telephone telephone VARCHAR(8) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE mot_de_passe mot_de_passe VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE role role VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE cin cin VARCHAR(8) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE fournisseur CHANGE nom nom VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE adresse adresse VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE telephone telephone VARCHAR(10) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE ingrediant CHANGE nom nom VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE menu_element CHANGE nom nom VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE categorie categorie VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE image image VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE options options LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE promotion CHANGE banner banner VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE reclamation CHANGE sujet sujet VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE contenu contenu LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE statut statut VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE reponse CHANGE contenu contenu LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE reservation CHANGE statut statut VARCHAR(20) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE restaurant CHANGE nom nom VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE adresse adresse VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE architecture architecture VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE telephone telephone VARCHAR(10) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE images images LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE `table` ADD type INT NOT NULL');
    }
}
