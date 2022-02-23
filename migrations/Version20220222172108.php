<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220222172108 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP heure');
        $this->addSql('ALTER TABLE blog_client ADD image_blog VARCHAR(255) NOT NULL, DROP heure');
        $this->addSql('ALTER TABLE commentaire DROP heure');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD heure TIME NOT NULL');
        $this->addSql('ALTER TABLE blog_client ADD heure TIME NOT NULL, DROP image_blog');
        $this->addSql('ALTER TABLE commentaire ADD heure TIME NOT NULL');
    }
}
