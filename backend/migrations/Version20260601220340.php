<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260601220340 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY `FK_Utiliser`');
        $this->addSql('ALTER TABLE utilisateur CHANGE telUtilisateur telUtilisateur VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_TEL_UTILISATEUR ON utilisateur (telUtilisateur)');
        $this->addSql('DROP INDEX fk_utiliser ON utilisateur');
        $this->addSql('CREATE INDEX IDX_9B80EC64DC499668 ON utilisateur (id_role)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT `FK_Utiliser` FOREIGN KEY (id_role) REFERENCES roleutilisateur (id_role)');
        $this->addSql('ALTER TABLE campagne DROP FOREIGN KEY `FK_Lancer`');
        $this->addSql('ALTER TABLE campagne CHANGE id_culture id_culture BIGINT NOT NULL');
        $this->addSql('ALTER TABLE campagne ADD CONSTRAINT FK_539B5D166834359B FOREIGN KEY (id_culture) REFERENCES culture (id_culture)');
        $this->addSql('CREATE INDEX IDX_539B5D166834359B ON campagne (id_culture)');
        $this->addSql('DROP INDEX fk_lancer ON campagne');
        $this->addSql('CREATE INDEX IDX_539B5D1695B5C063 ON campagne (id_parcelle)');
        $this->addSql('ALTER TABLE campagne ADD CONSTRAINT `FK_Lancer` FOREIGN KEY (id_parcelle) REFERENCES parcelle (id_parcelle)');
        $this->addSql('ALTER TABLE connexion DROP FOREIGN KEY `FK_Association_16`');
        $this->addSql('DROP INDEX FK_Association_16 ON connexion');
        $this->addSql('ALTER TABLE connexion ADD date_connexion DATETIME NOT NULL, DROP dateConnexion, CHANGE id_connexion id_connexion BIGINT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE culture DROP FOREIGN KEY `FK_appartenir`');
        $this->addSql('DROP INDEX fk_appartenir ON culture');
        $this->addSql('CREATE INDEX IDX_B6A99CEBEEC2AC69 ON culture (id_tp_culture)');
        $this->addSql('ALTER TABLE culture ADD CONSTRAINT `FK_appartenir` FOREIGN KEY (id_tp_culture) REFERENCES type_culture (id_tp_culture)');
        $this->addSql('ALTER TABLE depenses DROP FOREIGN KEY `FK_Association_14`');
        $this->addSql('ALTER TABLE depenses DROP FOREIGN KEY `FK_Association_15`');
        $this->addSql('DROP INDEX FK_Association_14 ON depenses');
        $this->addSql('DROP INDEX FK_Association_15 ON depenses');
        $this->addSql('ALTER TABLE depenses ADD date_montant DATETIME NOT NULL, DROP dateMontant, CHANGE id_depenses id_depenses BIGINT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE intrant DROP FOREIGN KEY `FK_Association_13`');
        $this->addSql('ALTER TABLE intrant DROP FOREIGN KEY `FK_associer`');
        $this->addSql('DROP INDEX FK_Association_13 ON intrant');
        $this->addSql('DROP INDEX FK_associer ON intrant');
        $this->addSql('ALTER TABLE intrant CHANGE id_intrant id_intrant BIGINT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE ouvrier DROP FOREIGN KEY `FK_OUVRIER_USER`');
        $this->addSql('DROP INDEX fk_ouvrier_user ON ouvrier');
        $this->addSql('CREATE INDEX IDX_ED5E7D2550EAE44 ON ouvrier (id_utilisateur)');
        $this->addSql('ALTER TABLE ouvrier ADD CONSTRAINT `FK_OUVRIER_USER` FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id_utilisateur)');
        $this->addSql('ALTER TABLE parcelle DROP FOREIGN KEY `FK_contenir`');
        $this->addSql('ALTER TABLE parcelle CHANGE superficieParc superficieParc DOUBLE PRECISION NOT NULL');
        $this->addSql('DROP INDEX fk_contenir ON parcelle');
        $this->addSql('CREATE INDEX IDX_C56E2CF61203656D ON parcelle (id_plantation)');
        $this->addSql('ALTER TABLE parcelle ADD CONSTRAINT `FK_contenir` FOREIGN KEY (id_plantation) REFERENCES plantation (id_plantation)');
        $this->addSql('ALTER TABLE plantation DROP FOREIGN KEY `FK_Posseder`');
        $this->addSql('ALTER TABLE plantation CHANGE longitude longitude DOUBLE PRECISION NOT NULL, CHANGE latitude latitude DOUBLE PRECISION NOT NULL, CHANGE indications indications VARCHAR(300) NOT NULL');
        $this->addSql('DROP INDEX fk_posseder ON plantation');
        $this->addSql('CREATE INDEX IDX_B789E5BA50EAE44 ON plantation (id_utilisateur)');
        $this->addSql('ALTER TABLE plantation ADD CONSTRAINT `FK_Posseder` FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id_utilisateur)');
        $this->addSql('ALTER TABLE realiser ADD CONSTRAINT FK_7BAB8D077D026145 FOREIGN KEY (id_tache) REFERENCES tache (id_tache)');
        $this->addSql('ALTER TABLE realiser ADD CONSTRAINT FK_7BAB8D07340B183 FOREIGN KEY (id_campagne) REFERENCES campagne (id_campagne)');
        $this->addSql('ALTER TABLE realiser ADD CONSTRAINT FK_7BAB8D0733C3D455 FOREIGN KEY (id_ouvrier) REFERENCES ouvrier (id_ouvrier)');
        $this->addSql('ALTER TABLE realiser ADD CONSTRAINT FK_7BAB8D0747466D11 FOREIGN KEY (id_intrant) REFERENCES intrant (id_intrant)');
        $this->addSql('DROP INDEX id_tache ON realiser');
        $this->addSql('CREATE INDEX IDX_7BAB8D077D026145 ON realiser (id_tache)');
        $this->addSql('DROP INDEX id_campagne ON realiser');
        $this->addSql('CREATE INDEX IDX_7BAB8D07340B183 ON realiser (id_campagne)');
        $this->addSql('DROP INDEX id_ouvrier ON realiser');
        $this->addSql('CREATE INDEX IDX_7BAB8D0733C3D455 ON realiser (id_ouvrier)');
        $this->addSql('DROP INDEX id_intrant ON realiser');
        $this->addSql('CREATE INDEX IDX_7BAB8D0747466D11 ON realiser (id_intrant)');
        $this->addSql('ALTER TABLE recolte DROP FOREIGN KEY `FK_Association_12`');
        $this->addSql('ALTER TABLE recolte DROP FOREIGN KEY `FK_Produire`');
        $this->addSql('DROP INDEX FK_Produire ON recolte');
        $this->addSql('DROP INDEX FK_Association_12 ON recolte');
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
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE campagne DROP FOREIGN KEY FK_539B5D166834359B');
        $this->addSql('DROP INDEX IDX_539B5D166834359B ON campagne');
        $this->addSql('ALTER TABLE campagne DROP FOREIGN KEY FK_539B5D1695B5C063');
        $this->addSql('ALTER TABLE campagne CHANGE id_culture id_culture INT NOT NULL');
        $this->addSql('DROP INDEX idx_539b5d1695b5c063 ON campagne');
        $this->addSql('CREATE INDEX FK_Lancer ON campagne (id_parcelle)');
        $this->addSql('ALTER TABLE campagne ADD CONSTRAINT FK_539B5D1695B5C063 FOREIGN KEY (id_parcelle) REFERENCES parcelle (id_parcelle)');
        $this->addSql('ALTER TABLE connexion ADD dateConnexion DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, DROP date_connexion, CHANGE id_connexion id_connexion BIGINT NOT NULL');
        $this->addSql('ALTER TABLE connexion ADD CONSTRAINT `FK_Association_16` FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id_utilisateur)');
        $this->addSql('CREATE INDEX FK_Association_16 ON connexion (id_utilisateur)');
        $this->addSql('ALTER TABLE culture DROP FOREIGN KEY FK_B6A99CEBEEC2AC69');
        $this->addSql('DROP INDEX idx_b6a99cebeec2ac69 ON culture');
        $this->addSql('CREATE INDEX FK_appartenir ON culture (id_tp_culture)');
        $this->addSql('ALTER TABLE culture ADD CONSTRAINT FK_B6A99CEBEEC2AC69 FOREIGN KEY (id_tp_culture) REFERENCES type_culture (id_tp_culture)');
        $this->addSql('ALTER TABLE depenses ADD dateMontant DATE NOT NULL, DROP date_montant, CHANGE id_depenses id_depenses BIGINT NOT NULL');
        $this->addSql('ALTER TABLE depenses ADD CONSTRAINT `FK_Association_14` FOREIGN KEY (id_plantation) REFERENCES plantation (id_plantation)');
        $this->addSql('ALTER TABLE depenses ADD CONSTRAINT `FK_Association_15` FOREIGN KEY (id_intrant) REFERENCES intrant (id_intrant)');
        $this->addSql('CREATE INDEX FK_Association_14 ON depenses (id_plantation)');
        $this->addSql('CREATE INDEX FK_Association_15 ON depenses (id_intrant)');
        $this->addSql('ALTER TABLE intrant CHANGE id_intrant id_intrant BIGINT NOT NULL');
        $this->addSql('ALTER TABLE intrant ADD CONSTRAINT `FK_Association_13` FOREIGN KEY (id_unite) REFERENCES unite (id_unite)');
        $this->addSql('ALTER TABLE intrant ADD CONSTRAINT `FK_associer` FOREIGN KEY (id_tp_intrant) REFERENCES type_intrant (id_tp_intrant)');
        $this->addSql('CREATE INDEX FK_Association_13 ON intrant (id_unite)');
        $this->addSql('CREATE INDEX FK_associer ON intrant (id_tp_intrant)');
        $this->addSql('ALTER TABLE ouvrier DROP FOREIGN KEY FK_ED5E7D2550EAE44');
        $this->addSql('DROP INDEX idx_ed5e7d2550eae44 ON ouvrier');
        $this->addSql('CREATE INDEX FK_OUVRIER_USER ON ouvrier (id_utilisateur)');
        $this->addSql('ALTER TABLE ouvrier ADD CONSTRAINT FK_ED5E7D2550EAE44 FOREIGN KEY (id_utilisateur) REFERENCES Utilisateur (id_utilisateur)');
        $this->addSql('ALTER TABLE parcelle DROP FOREIGN KEY FK_C56E2CF61203656D');
        $this->addSql('ALTER TABLE parcelle CHANGE superficieParc superficieParc FLOAT DEFAULT NULL');
        $this->addSql('DROP INDEX idx_c56e2cf61203656d ON parcelle');
        $this->addSql('CREATE INDEX FK_contenir ON parcelle (id_plantation)');
        $this->addSql('ALTER TABLE parcelle ADD CONSTRAINT FK_C56E2CF61203656D FOREIGN KEY (id_plantation) REFERENCES plantation (id_plantation)');
        $this->addSql('ALTER TABLE plantation DROP FOREIGN KEY FK_B789E5BA50EAE44');
        $this->addSql('ALTER TABLE plantation CHANGE longitude longitude FLOAT NOT NULL, CHANGE latitude latitude FLOAT NOT NULL, CHANGE indications indications TEXT NOT NULL');
        $this->addSql('DROP INDEX idx_b789e5ba50eae44 ON plantation');
        $this->addSql('CREATE INDEX FK_Posseder ON plantation (id_utilisateur)');
        $this->addSql('ALTER TABLE plantation ADD CONSTRAINT FK_B789E5BA50EAE44 FOREIGN KEY (id_utilisateur) REFERENCES Utilisateur (id_utilisateur)');
        $this->addSql('ALTER TABLE realiser DROP FOREIGN KEY FK_7BAB8D077D026145');
        $this->addSql('ALTER TABLE realiser DROP FOREIGN KEY FK_7BAB8D07340B183');
        $this->addSql('ALTER TABLE realiser DROP FOREIGN KEY FK_7BAB8D0733C3D455');
        $this->addSql('ALTER TABLE realiser DROP FOREIGN KEY FK_7BAB8D0747466D11');
        $this->addSql('ALTER TABLE realiser DROP FOREIGN KEY FK_7BAB8D077D026145');
        $this->addSql('ALTER TABLE realiser DROP FOREIGN KEY FK_7BAB8D07340B183');
        $this->addSql('ALTER TABLE realiser DROP FOREIGN KEY FK_7BAB8D0733C3D455');
        $this->addSql('ALTER TABLE realiser DROP FOREIGN KEY FK_7BAB8D0747466D11');
        $this->addSql('DROP INDEX idx_7bab8d0733c3d455 ON realiser');
        $this->addSql('CREATE INDEX id_ouvrier ON realiser (id_ouvrier)');
        $this->addSql('DROP INDEX idx_7bab8d0747466d11 ON realiser');
        $this->addSql('CREATE INDEX id_intrant ON realiser (id_intrant)');
        $this->addSql('DROP INDEX idx_7bab8d077d026145 ON realiser');
        $this->addSql('CREATE INDEX id_tache ON realiser (id_tache)');
        $this->addSql('DROP INDEX idx_7bab8d07340b183 ON realiser');
        $this->addSql('CREATE INDEX id_campagne ON realiser (id_campagne)');
        $this->addSql('ALTER TABLE realiser ADD CONSTRAINT FK_7BAB8D077D026145 FOREIGN KEY (id_tache) REFERENCES tache (id_tache)');
        $this->addSql('ALTER TABLE realiser ADD CONSTRAINT FK_7BAB8D07340B183 FOREIGN KEY (id_campagne) REFERENCES campagne (id_campagne)');
        $this->addSql('ALTER TABLE realiser ADD CONSTRAINT FK_7BAB8D0733C3D455 FOREIGN KEY (id_ouvrier) REFERENCES ouvrier (id_ouvrier)');
        $this->addSql('ALTER TABLE realiser ADD CONSTRAINT FK_7BAB8D0747466D11 FOREIGN KEY (id_intrant) REFERENCES intrant (id_intrant)');
        $this->addSql('ALTER TABLE recolte ADD dateRecolte DATE NOT NULL, ADD dateVente DATE NOT NULL, ADD prixUnitaire DOUBLE PRECISION NOT NULL, ADD quantiteVendue DOUBLE PRECISION NOT NULL, DROP date_recolte, DROP date_vente, DROP prix_unitaire, DROP quantite_vendue, CHANGE id_recolte id_recolte BIGINT NOT NULL, CHANGE mode_paiement modePaiement VARCHAR(30) NOT NULL, CHANGE statut_paiement statutPaiement VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE recolte ADD CONSTRAINT `FK_Association_12` FOREIGN KEY (id_unite) REFERENCES unite (id_unite)');
        $this->addSql('ALTER TABLE recolte ADD CONSTRAINT `FK_Produire` FOREIGN KEY (id_campagne) REFERENCES campagne (id_campagne)');
        $this->addSql('CREATE INDEX FK_Produire ON recolte (id_campagne)');
        $this->addSql('CREATE INDEX FK_Association_12 ON recolte (id_unite)');
        $this->addSql('ALTER TABLE tache DROP FOREIGN KEY FK_938720754433ED66');
        $this->addSql('ALTER TABLE tache DROP FOREIGN KEY FK_938720754E853A9E');
        $this->addSql('DROP INDEX uniq_9387207549afb681 ON tache');
        $this->addSql('CREATE UNIQUE INDEX uuid_local ON tache (uuid_local)');
        $this->addSql('DROP INDEX idx_938720754433ed66 ON tache');
        $this->addSql('CREATE INDEX parcelle_id ON tache (parcelle_id)');
        $this->addSql('DROP INDEX idx_938720754e853a9e ON tache');
        $this->addSql('CREATE INDEX FK_TACHE_OUVRIER ON tache (ouvrier_id)');
        $this->addSql('ALTER TABLE tache ADD CONSTRAINT FK_938720754433ED66 FOREIGN KEY (parcelle_id) REFERENCES parcelle (id_parcelle) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE tache ADD CONSTRAINT FK_938720754E853A9E FOREIGN KEY (ouvrier_id) REFERENCES ouvrier (id_ouvrier) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE type_culture CHANGE id_tp_culture id_tp_culture BIGINT NOT NULL');
        $this->addSql('ALTER TABLE type_intrant CHANGE id_tp_intrant id_tp_intrant BIGINT NOT NULL, CHANGE nom_tp_intrant nomTpIntrant VARCHAR(30) NOT NULL');
        $this->addSql('ALTER TABLE unite CHANGE id_unite id_unite BIGINT NOT NULL, CHANGE nom_unite nomUnite VARCHAR(30) NOT NULL');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_TEL_UTILISATEUR ON Utilisateur');
        $this->addSql('ALTER TABLE Utilisateur DROP FOREIGN KEY FK_9B80EC64DC499668');
        $this->addSql('ALTER TABLE Utilisateur CHANGE telUtilisateur telUtilisateur VARCHAR(20) NOT NULL');
        $this->addSql('DROP INDEX idx_9b80ec64dc499668 ON Utilisateur');
        $this->addSql('CREATE INDEX FK_Utiliser ON Utilisateur (id_role)');
        $this->addSql('ALTER TABLE Utilisateur ADD CONSTRAINT FK_9B80EC64DC499668 FOREIGN KEY (id_role) REFERENCES RoleUtilisateur (id_role)');
    }
}
