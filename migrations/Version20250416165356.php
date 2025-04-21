<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250416165356 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE league (id INT AUTO_INCREMENT NOT NULL, region_id INT NOT NULL, name VARCHAR(255) NOT NULL, level INT NOT NULL, INDEX IDX_3EB4C31898260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, sport_id INT NOT NULL, name VARCHAR(255) NOT NULL, logo_path VARCHAR(255) DEFAULT NULL, INDEX IDX_F62F176AC78BCF8 (sport_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sport (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, logo_path VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE league ADD CONSTRAINT FK_3EB4C31898260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE region ADD CONSTRAINT FK_F62F176AC78BCF8 FOREIGN KEY (sport_id) REFERENCES sport (id)');
        $this->addSql('ALTER TABLE messenger_messages CHANGE id id BIGINT AUTO_INCREMENT NOT NULL, CHANGE body body LONGTEXT NOT NULL, CHANGE headers headers LONGTEXT NOT NULL, CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE available_at available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE delivered_at delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE league DROP FOREIGN KEY FK_3EB4C31898260155');
        $this->addSql('ALTER TABLE region DROP FOREIGN KEY FK_F62F176AC78BCF8');
        $this->addSql('DROP TABLE league');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE sport');
        $this->addSql('ALTER TABLE messenger_messages CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE body body TEXT NOT NULL, CHANGE headers headers TEXT NOT NULL, CHANGE created_at created_at DATETIME NOT NULL, CHANGE available_at available_at DATETIME NOT NULL, CHANGE delivered_at delivered_at DATETIME DEFAULT NULL');
    }
}
