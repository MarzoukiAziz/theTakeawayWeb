<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220301011356 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE restaurant_favoris DROP FOREIGN KEY FK_43789847FD5363AC');
        $this->addSql('DROP INDEX IDX_43789847FD5363AC ON restaurant_favoris');
        $this->addSql('ALTER TABLE restaurant_favoris CHANGE restaurnant_id restaurant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE restaurant_favoris ADD CONSTRAINT FK_43789847B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('CREATE INDEX IDX_43789847B1E7706E ON restaurant_favoris (restaurant_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE restaurant_favoris DROP FOREIGN KEY FK_43789847B1E7706E');
        $this->addSql('DROP INDEX IDX_43789847B1E7706E ON restaurant_favoris');
        $this->addSql('ALTER TABLE restaurant_favoris CHANGE restaurant_id restaurnant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE restaurant_favoris ADD CONSTRAINT FK_43789847FD5363AC FOREIGN KEY (restaurnant_id) REFERENCES restaurant (id)');
        $this->addSql('CREATE INDEX IDX_43789847FD5363AC ON restaurant_favoris (restaurnant_id)');
    }
}
