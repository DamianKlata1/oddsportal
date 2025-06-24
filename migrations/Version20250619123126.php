<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250619123126 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event_odds_import_sync (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, bet_region_id INT NOT NULL, last_imported_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_C3621DB371F7E88B (event_id), INDEX IDX_C3621DB3A9E1DBC0 (bet_region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE league_odds_import_sync (id INT AUTO_INCREMENT NOT NULL, league_id INT NOT NULL, bet_region_id INT NOT NULL, last_imported_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_8EA8D7ED58AFC4DE (league_id), INDEX IDX_8EA8D7EDA9E1DBC0 (bet_region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event_odds_import_sync ADD CONSTRAINT FK_C3621DB371F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE event_odds_import_sync ADD CONSTRAINT FK_C3621DB3A9E1DBC0 FOREIGN KEY (bet_region_id) REFERENCES bet_region (id)');
        $this->addSql('ALTER TABLE league_odds_import_sync ADD CONSTRAINT FK_8EA8D7ED58AFC4DE FOREIGN KEY (league_id) REFERENCES league (id)');
        $this->addSql('ALTER TABLE league_odds_import_sync ADD CONSTRAINT FK_8EA8D7EDA9E1DBC0 FOREIGN KEY (bet_region_id) REFERENCES bet_region (id)');
        $this->addSql('ALTER TABLE odds_data_import_sync DROP FOREIGN KEY FK_F032278558AFC4DE');
        $this->addSql('ALTER TABLE odds_data_import_sync DROP FOREIGN KEY FK_F0322785A9E1DBC0');
        $this->addSql('DROP TABLE odds_data_import_sync');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE odds_data_import_sync (id INT AUTO_INCREMENT NOT NULL, league_id INT NOT NULL, bet_region_id INT NOT NULL, last_imported_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_F0322785A9E1DBC0 (bet_region_id), INDEX IDX_F032278558AFC4DE (league_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE odds_data_import_sync ADD CONSTRAINT FK_F032278558AFC4DE FOREIGN KEY (league_id) REFERENCES league (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE odds_data_import_sync ADD CONSTRAINT FK_F0322785A9E1DBC0 FOREIGN KEY (bet_region_id) REFERENCES bet_region (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE event_odds_import_sync DROP FOREIGN KEY FK_C3621DB371F7E88B');
        $this->addSql('ALTER TABLE event_odds_import_sync DROP FOREIGN KEY FK_C3621DB3A9E1DBC0');
        $this->addSql('ALTER TABLE league_odds_import_sync DROP FOREIGN KEY FK_8EA8D7ED58AFC4DE');
        $this->addSql('ALTER TABLE league_odds_import_sync DROP FOREIGN KEY FK_8EA8D7EDA9E1DBC0');
        $this->addSql('DROP TABLE event_odds_import_sync');
        $this->addSql('DROP TABLE league_odds_import_sync');
    }
}
