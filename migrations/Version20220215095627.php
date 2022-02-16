<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220215095627 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, telephone VARCHAR(8) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, cin VARCHAR(8) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, title VARCHAR(255) NOT NULL, date DATE NOT NULL, heure TIME NOT NULL, contenu LONGTEXT NOT NULL, banner VARCHAR(255) NOT NULL, INDEX IDX_23A0E66F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blog_client (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, title VARCHAR(255) NOT NULL, contenu LONGTEXT NOT NULL, date DATE NOT NULL, heure TIME NOT NULL, statut VARCHAR(255) NOT NULL, INDEX IDX_520BA194F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE carte_bancaire (id INT AUTO_INCREMENT NOT NULL, client_id_id INT NOT NULL, numero VARCHAR(16) NOT NULL, nom VARCHAR(255) NOT NULL, cvv VARCHAR(3) NOT NULL, email VARCHAR(255) NOT NULL, INDEX IDX_59E3C22DDC2902E0 (client_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_C7440455E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, restaurant_id_id INT DEFAULT NULL, client_id_id INT NOT NULL, employe_charge_id INT NOT NULL, carte_id_id INT DEFAULT NULL, prix_total DOUBLE PRECISION NOT NULL, statut VARCHAR(255) NOT NULL, date DATE NOT NULL, heure TIME NOT NULL, methode VARCHAR(255) NOT NULL, point_utilisees INT NOT NULL, statut_paiement VARCHAR(255) NOT NULL, INDEX IDX_6EEAA67D35592D86 (restaurant_id_id), INDEX IDX_6EEAA67DDC2902E0 (client_id_id), INDEX IDX_6EEAA67D94064F42 (employe_charge_id), INDEX IDX_6EEAA67DF7EBEA88 (carte_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, no_id INT NOT NULL, date DATE NOT NULL, contenu LONGTEXT NOT NULL, heure TIME NOT NULL, INDEX IDX_67F068BCF675F31B (author_id), INDEX IDX_67F068BC1A65C546 (no_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE element_details (id INT AUTO_INCREMENT NOT NULL, commande_id_id INT NOT NULL, element_id_id INT NOT NULL, quantite INT NOT NULL, options LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_85CD4BDE462C4194 (commande_id_id), INDEX IDX_85CD4BDEBB66EACE (element_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employe (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, telephone VARCHAR(8) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, cin VARCHAR(8) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etat_element (id INT AUTO_INCREMENT NOT NULL, restaurant_id INT NOT NULL, element_id INT NOT NULL, temps_attente INT NOT NULL, disponibilite TINYINT(1) NOT NULL, INDEX IDX_469F61BCB1E7706E (restaurant_id), INDEX IDX_469F61BC1F1F2A24 (element_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE facture (id INT AUTO_INCREMENT NOT NULL, fournisseur_id_id INT DEFAULT NULL, ingrediant_id_id INT NOT NULL, quantite INT NOT NULL, date DATE NOT NULL, heure TIME NOT NULL, prix_unitaire DOUBLE PRECISION NOT NULL, INDEX IDX_FE86641062122BA2 (fournisseur_id_id), INDEX IDX_FE8664103B9AA89A (ingrediant_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fournisseur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, telephone VARCHAR(10) NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ingrediant (id INT AUTO_INCREMENT NOT NULL, restaurant_id_id INT NOT NULL, nom VARCHAR(255) NOT NULL, quantite INT NOT NULL, INDEX IDX_6CA6D0AC35592D86 (restaurant_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu_element (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, prix DOUBLE PRECISION NOT NULL, categorie VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, options LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note (id INT AUTO_INCREMENT NOT NULL, client_id_id INT NOT NULL, element_id_id INT NOT NULL, note INT NOT NULL, INDEX IDX_CFBDFA14DC2902E0 (client_id_id), INDEX IDX_CFBDFA14BB66EACE (element_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion (id INT AUTO_INCREMENT NOT NULL, element_id INT NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, heure_debut TIME NOT NULL, heure_fin TIME NOT NULL, banner VARCHAR(255) NOT NULL, prix_promo DOUBLE PRECISION NOT NULL, INDEX IDX_C11D7DD11F1F2A24 (element_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, client_id_id INT NOT NULL, sujet VARCHAR(255) NOT NULL, contenu LONGTEXT NOT NULL, statut VARCHAR(255) NOT NULL, date DATE NOT NULL, heure TIME NOT NULL, INDEX IDX_CE606404DC2902E0 (client_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse (id INT AUTO_INCREMENT NOT NULL, reclamation_id INT NOT NULL, client_id INT DEFAULT NULL, admin_id INT DEFAULT NULL, contenu LONGTEXT NOT NULL, date DATE NOT NULL, heure TIME NOT NULL, INDEX IDX_5FB6DEC72D6BA2D9 (reclamation_id), INDEX IDX_5FB6DEC719EB6921 (client_id), INDEX IDX_5FB6DEC7642B8210 (admin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, client_id_id INT NOT NULL, admin_charge_id INT DEFAULT NULL, date DATE NOT NULL, heure_arrive TIME NOT NULL, heure_depart TIME NOT NULL, nb_personne INT NOT NULL, statut VARCHAR(20) NOT NULL, INDEX IDX_42C84955DC2902E0 (client_id_id), INDEX IDX_42C84955BCDF4C26 (admin_charge_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation_table (reservation_id INT NOT NULL, table_id INT NOT NULL, INDEX IDX_B5565FE1B83297E7 (reservation_id), INDEX IDX_B5565FE1ECFF285C (table_id), PRIMARY KEY(reservation_id, table_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, heure_ouverture TIME NOT NULL, heure_fermeture TIME NOT NULL, architecture VARCHAR(255) NOT NULL, telephone VARCHAR(10) NOT NULL, images LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sortie (id INT AUTO_INCREMENT NOT NULL, ingrediant_id_id INT DEFAULT NULL, quantite INT NOT NULL, date DATE NOT NULL, heure TIME NOT NULL, INDEX IDX_3C3FD3F23B9AA89A (ingrediant_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `table` (id INT AUTO_INCREMENT NOT NULL, restaurant_id_id INT NOT NULL, pos_x INT NOT NULL, pos_y INT NOT NULL, nb_palces INT NOT NULL, numero INT NOT NULL, INDEX IDX_F6298F4635592D86 (restaurant_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66F675F31B FOREIGN KEY (author_id) REFERENCES admin (id)');
        $this->addSql('ALTER TABLE blog_client ADD CONSTRAINT FK_520BA194F675F31B FOREIGN KEY (author_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE carte_bancaire ADD CONSTRAINT FK_59E3C22DDC2902E0 FOREIGN KEY (client_id_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D35592D86 FOREIGN KEY (restaurant_id_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DDC2902E0 FOREIGN KEY (client_id_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D94064F42 FOREIGN KEY (employe_charge_id) REFERENCES employe (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DF7EBEA88 FOREIGN KEY (carte_id_id) REFERENCES carte_bancaire (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCF675F31B FOREIGN KEY (author_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC1A65C546 FOREIGN KEY (no_id) REFERENCES blog_client (id)');
        $this->addSql('ALTER TABLE element_details ADD CONSTRAINT FK_85CD4BDE462C4194 FOREIGN KEY (commande_id_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE element_details ADD CONSTRAINT FK_85CD4BDEBB66EACE FOREIGN KEY (element_id_id) REFERENCES menu_element (id)');
        $this->addSql('ALTER TABLE etat_element ADD CONSTRAINT FK_469F61BCB1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE etat_element ADD CONSTRAINT FK_469F61BC1F1F2A24 FOREIGN KEY (element_id) REFERENCES menu_element (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE86641062122BA2 FOREIGN KEY (fournisseur_id_id) REFERENCES fournisseur (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE8664103B9AA89A FOREIGN KEY (ingrediant_id_id) REFERENCES ingrediant (id)');
        $this->addSql('ALTER TABLE ingrediant ADD CONSTRAINT FK_6CA6D0AC35592D86 FOREIGN KEY (restaurant_id_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14DC2902E0 FOREIGN KEY (client_id_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14BB66EACE FOREIGN KEY (element_id_id) REFERENCES menu_element (id)');
        $this->addSql('ALTER TABLE promotion ADD CONSTRAINT FK_C11D7DD11F1F2A24 FOREIGN KEY (element_id) REFERENCES menu_element (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404DC2902E0 FOREIGN KEY (client_id_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC72D6BA2D9 FOREIGN KEY (reclamation_id) REFERENCES reclamation (id)');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC719EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC7642B8210 FOREIGN KEY (admin_id) REFERENCES admin (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955DC2902E0 FOREIGN KEY (client_id_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955BCDF4C26 FOREIGN KEY (admin_charge_id) REFERENCES admin (id)');
        $this->addSql('ALTER TABLE reservation_table ADD CONSTRAINT FK_B5565FE1B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation_table ADD CONSTRAINT FK_B5565FE1ECFF285C FOREIGN KEY (table_id) REFERENCES `table` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sortie ADD CONSTRAINT FK_3C3FD3F23B9AA89A FOREIGN KEY (ingrediant_id_id) REFERENCES ingrediant (id)');
        $this->addSql('ALTER TABLE `table` ADD CONSTRAINT FK_F6298F4635592D86 FOREIGN KEY (restaurant_id_id) REFERENCES restaurant (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66F675F31B');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC7642B8210');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955BCDF4C26');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC1A65C546');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DF7EBEA88');
        $this->addSql('ALTER TABLE blog_client DROP FOREIGN KEY FK_520BA194F675F31B');
        $this->addSql('ALTER TABLE carte_bancaire DROP FOREIGN KEY FK_59E3C22DDC2902E0');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DDC2902E0');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCF675F31B');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14DC2902E0');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404DC2902E0');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC719EB6921');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955DC2902E0');
        $this->addSql('ALTER TABLE element_details DROP FOREIGN KEY FK_85CD4BDE462C4194');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D94064F42');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE86641062122BA2');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE8664103B9AA89A');
        $this->addSql('ALTER TABLE sortie DROP FOREIGN KEY FK_3C3FD3F23B9AA89A');
        $this->addSql('ALTER TABLE element_details DROP FOREIGN KEY FK_85CD4BDEBB66EACE');
        $this->addSql('ALTER TABLE etat_element DROP FOREIGN KEY FK_469F61BC1F1F2A24');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14BB66EACE');
        $this->addSql('ALTER TABLE promotion DROP FOREIGN KEY FK_C11D7DD11F1F2A24');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC72D6BA2D9');
        $this->addSql('ALTER TABLE reservation_table DROP FOREIGN KEY FK_B5565FE1B83297E7');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D35592D86');
        $this->addSql('ALTER TABLE etat_element DROP FOREIGN KEY FK_469F61BCB1E7706E');
        $this->addSql('ALTER TABLE ingrediant DROP FOREIGN KEY FK_6CA6D0AC35592D86');
        $this->addSql('ALTER TABLE `table` DROP FOREIGN KEY FK_F6298F4635592D86');
        $this->addSql('ALTER TABLE reservation_table DROP FOREIGN KEY FK_B5565FE1ECFF285C');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE blog_client');
        $this->addSql('DROP TABLE carte_bancaire');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE element_details');
        $this->addSql('DROP TABLE employe');
        $this->addSql('DROP TABLE etat_element');
        $this->addSql('DROP TABLE facture');
        $this->addSql('DROP TABLE fournisseur');
        $this->addSql('DROP TABLE ingrediant');
        $this->addSql('DROP TABLE menu_element');
        $this->addSql('DROP TABLE note');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE reservation_table');
        $this->addSql('DROP TABLE restaurant');
        $this->addSql('DROP TABLE sortie');
        $this->addSql('DROP TABLE `table`');
    }
}
