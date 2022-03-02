<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220228220730 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE restaurant CHANGE heure_ouverture heure_ouverture VARCHAR(255) DEFAULT NULL, CHANGE heure_fermeture heure_fermeture VARCHAR(255) DEFAULT NULL, CHANGE architecture architecture VARCHAR(255) DEFAULT NULL, CHANGE telephone telephone VARCHAR(10) DEFAULT NULL, CHANGE images images LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE restaurant CHANGE heure_ouverture heure_ouverture TIME NOT NULL, CHANGE heure_fermeture heure_fermeture TIME NOT NULL, CHANGE architecture architecture VARCHAR(255) NOT NULL, CHANGE telephone telephone VARCHAR(10) NOT NULL, CHANGE images images LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
    }
}
