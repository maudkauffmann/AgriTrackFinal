<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260605174538 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE intrant ADD CONSTRAINT FK_99DBC461F3E18028 FOREIGN KEY (id_unite) REFERENCES unite (id_unite)');
        $this->addSql('CREATE INDEX IDX_99DBC461F3E18028 ON intrant (id_unite)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE intrant DROP FOREIGN KEY FK_99DBC461F3E18028');
        $this->addSql('DROP INDEX IDX_99DBC461F3E18028 ON intrant');
    }
}
