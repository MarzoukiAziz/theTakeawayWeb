<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220303135345 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE carte_bancaire');
        $this->addSql('ALTER TABLE cart_bancaire ADD client_id INT NOT NULL');
        $this->addSql('ALTER TABLE cart_bancaire ADD CONSTRAINT FK_4C93FD4819EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_4C93FD4819EB6921 ON cart_bancaire (client_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE carte_bancaire (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, INDEX IDX_59E3C22D19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE carte_bancaire ADD CONSTRAINT FK_59E3C22D19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE cart_bancaire DROP FOREIGN KEY FK_4C93FD4819EB6921');
        $this->addSql('DROP INDEX IDX_4C93FD4819EB6921 ON cart_bancaire');
        $this->addSql('ALTER TABLE cart_bancaire DROP client_id');
    }
}
