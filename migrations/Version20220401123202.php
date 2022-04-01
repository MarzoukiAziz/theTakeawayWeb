<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220401123202 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCBC95816E');
        $this->addSql('DROP INDEX IDX_67F068BCBC95816E ON commentaire');
        $this->addSql('ALTER TABLE commentaire DROP blog_client_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire ADD blog_client_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCBC95816E FOREIGN KEY (blog_client_id) REFERENCES blog_client (id)');
        $this->addSql('CREATE INDEX IDX_67F068BCBC95816E ON commentaire (blog_client_id)');
    }
}
