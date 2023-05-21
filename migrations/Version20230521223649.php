<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230521223649 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client ADD chambre_hote_id INT NOT NULL');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455E62B6E93 FOREIGN KEY (chambre_hote_id) REFERENCES chambre_hote (id)');
        $this->addSql('CREATE INDEX IDX_C7440455E62B6E93 ON client (chambre_hote_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455E62B6E93');
        $this->addSql('DROP INDEX IDX_C7440455E62B6E93 ON client');
        $this->addSql('ALTER TABLE client DROP chambre_hote_id');
    }
}
