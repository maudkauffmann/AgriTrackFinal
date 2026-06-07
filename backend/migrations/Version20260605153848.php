<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260605153848 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE intrant DROP FOREIGN KEY `FK_99DBC461C1B0F4E3`');
        $this->addSql('DROP INDEX fk_99dbc461c1b0f4e3 ON intrant');
        $this->addSql('CREATE INDEX IDX_99DBC461C1B0F4E3 ON intrant (id_tp_intrant)');
        $this->addSql('ALTER TABLE intrant ADD CONSTRAINT `FK_99DBC461C1B0F4E3` FOREIGN KEY (id_tp_intrant) REFERENCES type_intrant (id_tp_intrant)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE intrant DROP FOREIGN KEY FK_99DBC461C1B0F4E3');
        $this->addSql('DROP INDEX idx_99dbc461c1b0f4e3 ON intrant');
        $this->addSql('CREATE INDEX FK_99DBC461C1B0F4E3 ON intrant (id_tp_intrant)');
        $this->addSql('ALTER TABLE intrant ADD CONSTRAINT FK_99DBC461C1B0F4E3 FOREIGN KEY (id_tp_intrant) REFERENCES type_intrant (id_tp_intrant)');
    }
}
