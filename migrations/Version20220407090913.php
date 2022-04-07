<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220407090913 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE client_restaurant');
        $this->addSql('DROP TABLE etat_element');
        $this->addSql('DROP TABLE note');
        $this->addSql('DROP TABLE sortie');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client_restaurant (client_id INT NOT NULL, restaurant_id INT NOT NULL, INDEX IDX_7BC12F7E19EB6921 (client_id), INDEX IDX_7BC12F7EB1E7706E (restaurant_id), PRIMARY KEY(client_id, restaurant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE etat_element (id INT AUTO_INCREMENT NOT NULL, restaurant_id INT NOT NULL, element_id INT NOT NULL, temps_attente INT NOT NULL, disponibilite TINYINT(1) NOT NULL, INDEX IDX_469F61BCB1E7706E (restaurant_id), INDEX IDX_469F61BC1F1F2A24 (element_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE note (id INT AUTO_INCREMENT NOT NULL, client_id_id INT NOT NULL, element_id_id INT NOT NULL, note INT NOT NULL, INDEX IDX_CFBDFA14DC2902E0 (client_id_id), INDEX IDX_CFBDFA14BB66EACE (element_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE sortie (id INT AUTO_INCREMENT NOT NULL, ingrediant_id_id INT DEFAULT NULL, quantite INT NOT NULL, date DATE NOT NULL, heure TIME NOT NULL, INDEX IDX_3C3FD3F23B9AA89A (ingrediant_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE client_restaurant ADD CONSTRAINT FK_7BC12F7E19EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE client_restaurant ADD CONSTRAINT FK_7BC12F7EB1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE etat_element ADD CONSTRAINT FK_469F61BC1F1F2A24 FOREIGN KEY (element_id) REFERENCES menu_element (id)');
        $this->addSql('ALTER TABLE etat_element ADD CONSTRAINT FK_469F61BCB1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14BB66EACE FOREIGN KEY (element_id_id) REFERENCES menu_element (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14DC2902E0 FOREIGN KEY (client_id_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE sortie ADD CONSTRAINT FK_3C3FD3F23B9AA89A FOREIGN KEY (ingrediant_id_id) REFERENCES ingrediant (id)');
    }
}
