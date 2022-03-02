<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220228204008 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE restaurant_favoris (id INT AUTO_INCREMENT NOT NULL, restaurnant_id INT DEFAULT NULL, client_id INT DEFAULT NULL, INDEX IDX_43789847FD5363AC (restaurnant_id), INDEX IDX_4378984719EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE restaurant_favoris ADD CONSTRAINT FK_43789847FD5363AC FOREIGN KEY (restaurnant_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE restaurant_favoris ADD CONSTRAINT FK_4378984719EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE ingrediant CHANGE restaurant_id restaurant_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE restaurant_favoris');
        $this->addSql('ALTER TABLE ingrediant CHANGE restaurant_id restaurant_id INT DEFAULT NULL');
    }
}
