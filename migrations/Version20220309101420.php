<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220309101420 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D94064F42');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DC9C7CEB6');
        $this->addSql('DROP INDEX IDX_6EEAA67D94064F42 ON commande');
        $this->addSql('DROP INDEX IDX_6EEAA67DC9C7CEB6 ON commande');
        $this->addSql('ALTER TABLE commande DROP employe_charge_id, DROP carte_id');
        $this->addSql('ALTER TABLE element_details CHANGE options options VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande ADD employe_charge_id INT NOT NULL, ADD carte_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D94064F42 FOREIGN KEY (employe_charge_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DC9C7CEB6 FOREIGN KEY (carte_id) REFERENCES cart_bancaire (id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67D94064F42 ON commande (employe_charge_id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67DC9C7CEB6 ON commande (carte_id)');
        $this->addSql('ALTER TABLE element_details CHANGE options options LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
    }
}
