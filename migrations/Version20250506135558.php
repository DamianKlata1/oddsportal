<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250506135558 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bet_region (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, logo_path VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_634B2E315E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bookmaker (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bookmaker_bet_region (bookmaker_id INT NOT NULL, bet_region_id INT NOT NULL, INDEX IDX_ADD6C2508FB29728 (bookmaker_id), INDEX IDX_ADD6C250A9E1DBC0 (bet_region_id), PRIMARY KEY(bookmaker_id, bet_region_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, league_id INT NOT NULL, INDEX IDX_3BAE0AA758AFC4DE (league_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bookmaker_bet_region ADD CONSTRAINT FK_ADD6C2508FB29728 FOREIGN KEY (bookmaker_id) REFERENCES bookmaker (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bookmaker_bet_region ADD CONSTRAINT FK_ADD6C250A9E1DBC0 FOREIGN KEY (bet_region_id) REFERENCES bet_region (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA758AFC4DE FOREIGN KEY (league_id) REFERENCES league (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bookmaker_bet_region DROP FOREIGN KEY FK_ADD6C2508FB29728');
        $this->addSql('ALTER TABLE bookmaker_bet_region DROP FOREIGN KEY FK_ADD6C250A9E1DBC0');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA758AFC4DE');
        $this->addSql('DROP TABLE bet_region');
        $this->addSql('DROP TABLE bookmaker');
        $this->addSql('DROP TABLE bookmaker_bet_region');
        $this->addSql('DROP TABLE event');
    }
}
