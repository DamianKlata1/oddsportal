<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250612122247 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bet_region (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, logo_path VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_634B2E315E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bookmaker (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_82A144A75E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bookmaker_bet_region (bookmaker_id INT NOT NULL, bet_region_id INT NOT NULL, INDEX IDX_ADD6C2508FB29728 (bookmaker_id), INDEX IDX_ADD6C250A9E1DBC0 (bet_region_id), PRIMARY KEY(bookmaker_id, bet_region_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, league_id INT NOT NULL, home_team VARCHAR(255) DEFAULT NULL, away_team VARCHAR(255) DEFAULT NULL, commence_time DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', api_id VARCHAR(255) NOT NULL, INDEX IDX_3BAE0AA758AFC4DE (league_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE league (id INT AUTO_INCREMENT NOT NULL, region_id INT NOT NULL, name VARCHAR(255) NOT NULL, api_key VARCHAR(255) DEFAULT NULL, active TINYINT(1) NOT NULL, logo_path VARCHAR(255) NOT NULL, INDEX IDX_3EB4C31898260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE odds_data_import_sync (id INT AUTO_INCREMENT NOT NULL, league_id INT NOT NULL, bet_region_id INT NOT NULL, last_imported_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_F032278558AFC4DE (league_id), INDEX IDX_F0322785A9E1DBC0 (bet_region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE outcome (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, bookmaker_id INT NOT NULL, name VARCHAR(255) NOT NULL, price NUMERIC(10, 2) NOT NULL, market VARCHAR(255) NOT NULL, last_update DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_30BC6DC271F7E88B (event_id), INDEX IDX_30BC6DC28FB29728 (bookmaker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE refresh_tokens (id INT AUTO_INCREMENT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid DATETIME NOT NULL, UNIQUE INDEX UNIQ_9BACE7E1C74F2195 (refresh_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, sport_id INT NOT NULL, name VARCHAR(255) NOT NULL, logo_path VARCHAR(255) DEFAULT NULL, INDEX IDX_F62F176AC78BCF8 (sport_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sport (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, logo_path VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_1A85EFD25E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_league (user_id INT NOT NULL, league_id INT NOT NULL, INDEX IDX_5BE6D825A76ED395 (user_id), INDEX IDX_5BE6D82558AFC4DE (league_id), PRIMARY KEY(user_id, league_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bookmaker_bet_region ADD CONSTRAINT FK_ADD6C2508FB29728 FOREIGN KEY (bookmaker_id) REFERENCES bookmaker (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bookmaker_bet_region ADD CONSTRAINT FK_ADD6C250A9E1DBC0 FOREIGN KEY (bet_region_id) REFERENCES bet_region (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA758AFC4DE FOREIGN KEY (league_id) REFERENCES league (id)');
        $this->addSql('ALTER TABLE league ADD CONSTRAINT FK_3EB4C31898260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE odds_data_import_sync ADD CONSTRAINT FK_F032278558AFC4DE FOREIGN KEY (league_id) REFERENCES league (id)');
        $this->addSql('ALTER TABLE odds_data_import_sync ADD CONSTRAINT FK_F0322785A9E1DBC0 FOREIGN KEY (bet_region_id) REFERENCES bet_region (id)');
        $this->addSql('ALTER TABLE outcome ADD CONSTRAINT FK_30BC6DC271F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE outcome ADD CONSTRAINT FK_30BC6DC28FB29728 FOREIGN KEY (bookmaker_id) REFERENCES bookmaker (id)');
        $this->addSql('ALTER TABLE region ADD CONSTRAINT FK_F62F176AC78BCF8 FOREIGN KEY (sport_id) REFERENCES sport (id)');
        $this->addSql('ALTER TABLE user_league ADD CONSTRAINT FK_5BE6D825A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_league ADD CONSTRAINT FK_5BE6D82558AFC4DE FOREIGN KEY (league_id) REFERENCES league (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bookmaker_bet_region DROP FOREIGN KEY FK_ADD6C2508FB29728');
        $this->addSql('ALTER TABLE bookmaker_bet_region DROP FOREIGN KEY FK_ADD6C250A9E1DBC0');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA758AFC4DE');
        $this->addSql('ALTER TABLE league DROP FOREIGN KEY FK_3EB4C31898260155');
        $this->addSql('ALTER TABLE odds_data_import_sync DROP FOREIGN KEY FK_F032278558AFC4DE');
        $this->addSql('ALTER TABLE odds_data_import_sync DROP FOREIGN KEY FK_F0322785A9E1DBC0');
        $this->addSql('ALTER TABLE outcome DROP FOREIGN KEY FK_30BC6DC271F7E88B');
        $this->addSql('ALTER TABLE outcome DROP FOREIGN KEY FK_30BC6DC28FB29728');
        $this->addSql('ALTER TABLE region DROP FOREIGN KEY FK_F62F176AC78BCF8');
        $this->addSql('ALTER TABLE user_league DROP FOREIGN KEY FK_5BE6D825A76ED395');
        $this->addSql('ALTER TABLE user_league DROP FOREIGN KEY FK_5BE6D82558AFC4DE');
        $this->addSql('DROP TABLE bet_region');
        $this->addSql('DROP TABLE bookmaker');
        $this->addSql('DROP TABLE bookmaker_bet_region');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE league');
        $this->addSql('DROP TABLE odds_data_import_sync');
        $this->addSql('DROP TABLE outcome');
        $this->addSql('DROP TABLE refresh_tokens');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE sport');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE user_league');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
