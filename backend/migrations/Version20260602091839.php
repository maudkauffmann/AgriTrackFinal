<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260602091839 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE realiser DROP FOREIGN KEY `FK_7BAB8D0733C3D455`');
        $this->addSql('ALTER TABLE realiser DROP FOREIGN KEY `FK_7BAB8D07340B183`');
        $this->addSql('ALTER TABLE realiser DROP FOREIGN KEY `FK_7BAB8D0747466D11`');
        $this->addSql('ALTER TABLE realiser DROP FOREIGN KEY `FK_7BAB8D077D026145`');
        $this->addSql('DROP INDEX id_tache ON realiser');
        $this->addSql('CREATE INDEX IDX_7BAB8D077D026145 ON realiser (id_tache)');
        $this->addSql('DROP INDEX id_campagne ON realiser');
        $this->addSql('CREATE INDEX IDX_7BAB8D07340B183 ON realiser (id_campagne)');
        $this->addSql('DROP INDEX id_ouvrier ON realiser');
        $this->addSql('CREATE INDEX IDX_7BAB8D0733C3D455 ON realiser (id_ouvrier)');
        $this->addSql('DROP INDEX id_intrant ON realiser');
        $this->addSql('CREATE INDEX IDX_7BAB8D0747466D11 ON realiser (id_intrant)');
        $this->addSql('ALTER TABLE realiser ADD CONSTRAINT `FK_7BAB8D0733C3D455` FOREIGN KEY (id_ouvrier) REFERENCES ouvrier (id_ouvrier)');
        $this->addSql('ALTER TABLE realiser ADD CONSTRAINT `FK_7BAB8D07340B183` FOREIGN KEY (id_campagne) REFERENCES campagne (id_campagne)');
        $this->addSql('ALTER TABLE realiser ADD CONSTRAINT `FK_7BAB8D0747466D11` FOREIGN KEY (id_intrant) REFERENCES intrant (id_intrant)');
        $this->addSql('ALTER TABLE realiser ADD CONSTRAINT `FK_7BAB8D077D026145` FOREIGN KEY (id_tache) REFERENCES tache (id_tache)');
        $this->addSql('ALTER TABLE recolte DROP FOREIGN KEY `FK_Association_12`');
        $this->addSql('ALTER TABLE recolte DROP FOREIGN KEY `FK_Produire`');
        $this->addSql('DROP INDEX FK_Association_12 ON recolte');
        $this->addSql('DROP INDEX FK_Produire ON recolte');
        $this->addSql('ALTER TABLE recolte ADD date_recolte DATETIME NOT NULL, ADD date_vente DATETIME NOT NULL, ADD prix_unitaire DOUBLE PRECISION NOT NULL, ADD quantite_vendue DOUBLE PRECISION NOT NULL, DROP dateRecolte, DROP dateVente, DROP prixUnitaire, DROP quantiteVendue, CHANGE id_recolte id_recolte BIGINT AUTO_INCREMENT NOT NULL, CHANGE modePaiement mode_paiement VARCHAR(30) NOT NULL, CHANGE statutPaiement statut_paiement VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE tache DROP FOREIGN KEY `FK_TACHE_OUVRIER`');
        $this->addSql('ALTER TABLE tache DROP FOREIGN KEY `fk_tache_parcelle`');
        $this->addSql('DROP INDEX uuid_local ON tache');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9387207549AFB681 ON tache (uuid_local)');
        $this->addSql('DROP INDEX parcelle_id ON tache');
        $this->addSql('CREATE INDEX IDX_938720754433ED66 ON tache (parcelle_id)');
        $this->addSql('DROP INDEX fk_tache_ouvrier ON tache');
        $this->addSql('CREATE INDEX IDX_938720754E853A9E ON tache (ouvrier_id)');
        $this->addSql('ALTER TABLE tache ADD CONSTRAINT `FK_TACHE_OUVRIER` FOREIGN KEY (ouvrier_id) REFERENCES ouvrier (id_ouvrier) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE tache ADD CONSTRAINT `fk_tache_parcelle` FOREIGN KEY (parcelle_id) REFERENCES parcelle (id_parcelle) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE type_culture CHANGE id_tp_culture id_tp_culture BIGINT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE type_intrant CHANGE id_tp_intrant id_tp_intrant BIGINT AUTO_INCREMENT NOT NULL, CHANGE nomTpIntrant nom_tp_intrant VARCHAR(30) NOT NULL');
        $this->addSql('ALTER TABLE unite CHANGE id_unite id_unite BIGINT AUTO_INCREMENT NOT NULL, CHANGE nomUnite nom_unite VARCHAR(30) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE realiser DROP FOREIGN KEY FK_7BAB8D077D026145');
        $this->addSql('ALTER TABLE realiser DROP FOREIGN KEY FK_7BAB8D07340B183');
        $this->addSql('ALTER TABLE realiser DROP FOREIGN KEY FK_7BAB8D0733C3D455');
        $this->addSql('ALTER TABLE realiser DROP FOREIGN KEY FK_7BAB8D0747466D11');
        $this->addSql('DROP INDEX idx_7bab8d07340b183 ON realiser');
        $this->addSql('CREATE INDEX id_campagne ON realiser (id_campagne)');
        $this->addSql('DROP INDEX idx_7bab8d0733c3d455 ON realiser');
        $this->addSql('CREATE INDEX id_ouvrier ON realiser (id_ouvrier)');
        $this->addSql('DROP INDEX idx_7bab8d0747466d11 ON realiser');
        $this->addSql('CREATE INDEX id_intrant ON realiser (id_intrant)');
        $this->addSql('DROP INDEX idx_7bab8d077d026145 ON realiser');
        $this->addSql('CREATE INDEX id_tache ON realiser (id_tache)');
        $this->addSql('ALTER TABLE realiser ADD CONSTRAINT FK_7BAB8D077D026145 FOREIGN KEY (id_tache) REFERENCES tache (id_tache)');
        $this->addSql('ALTER TABLE realiser ADD CONSTRAINT FK_7BAB8D07340B183 FOREIGN KEY (id_campagne) REFERENCES campagne (id_campagne)');
        $this->addSql('ALTER TABLE realiser ADD CONSTRAINT FK_7BAB8D0733C3D455 FOREIGN KEY (id_ouvrier) REFERENCES ouvrier (id_ouvrier)');
        $this->addSql('ALTER TABLE realiser ADD CONSTRAINT FK_7BAB8D0747466D11 FOREIGN KEY (id_intrant) REFERENCES intrant (id_intrant)');
        $this->addSql('ALTER TABLE recolte ADD dateRecolte DATE NOT NULL, ADD dateVente DATE NOT NULL, ADD prixUnitaire DOUBLE PRECISION NOT NULL, ADD quantiteVendue DOUBLE PRECISION NOT NULL, DROP date_recolte, DROP date_vente, DROP prix_unitaire, DROP quantite_vendue, CHANGE id_recolte id_recolte BIGINT NOT NULL, CHANGE mode_paiement modePaiement VARCHAR(30) NOT NULL, CHANGE statut_paiement statutPaiement VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE recolte ADD CONSTRAINT `FK_Association_12` FOREIGN KEY (id_unite) REFERENCES unite (id_unite)');
        $this->addSql('ALTER TABLE recolte ADD CONSTRAINT `FK_Produire` FOREIGN KEY (id_campagne) REFERENCES campagne (id_campagne)');
        $this->addSql('CREATE INDEX FK_Association_12 ON recolte (id_unite)');
        $this->addSql('CREATE INDEX FK_Produire ON recolte (id_campagne)');
        $this->addSql('ALTER TABLE tache DROP FOREIGN KEY FK_938720754433ED66');
        $this->addSql('ALTER TABLE tache DROP FOREIGN KEY FK_938720754E853A9E');
        $this->addSql('DROP INDEX idx_938720754e853a9e ON tache');
        $this->addSql('CREATE INDEX FK_TACHE_OUVRIER ON tache (ouvrier_id)');
        $this->addSql('DROP INDEX uniq_9387207549afb681 ON tache');
        $this->addSql('CREATE UNIQUE INDEX uuid_local ON tache (uuid_local)');
        $this->addSql('DROP INDEX idx_938720754433ed66 ON tache');
        $this->addSql('CREATE INDEX parcelle_id ON tache (parcelle_id)');
        $this->addSql('ALTER TABLE tache ADD CONSTRAINT FK_938720754433ED66 FOREIGN KEY (parcelle_id) REFERENCES parcelle (id_parcelle) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE tache ADD CONSTRAINT FK_938720754E853A9E FOREIGN KEY (ouvrier_id) REFERENCES ouvrier (id_ouvrier) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE type_culture CHANGE id_tp_culture id_tp_culture BIGINT NOT NULL');
        $this->addSql('ALTER TABLE type_intrant CHANGE id_tp_intrant id_tp_intrant BIGINT NOT NULL, CHANGE nom_tp_intrant nomTpIntrant VARCHAR(30) NOT NULL');
        $this->addSql('ALTER TABLE unite CHANGE id_unite id_unite BIGINT NOT NULL, CHANGE nom_unite nomUnite VARCHAR(30) NOT NULL');
    }
}
