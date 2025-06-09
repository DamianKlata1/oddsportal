<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250606132123 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_league (user_id INT NOT NULL, league_id INT NOT NULL, INDEX IDX_5BE6D825A76ED395 (user_id), INDEX IDX_5BE6D82558AFC4DE (league_id), PRIMARY KEY(user_id, league_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_league ADD CONSTRAINT FK_5BE6D825A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_league ADD CONSTRAINT FK_5BE6D82558AFC4DE FOREIGN KEY (league_id) REFERENCES league (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_league DROP FOREIGN KEY FK_5BE6D825A76ED395');
        $this->addSql('ALTER TABLE user_league DROP FOREIGN KEY FK_5BE6D82558AFC4DE');
        $this->addSql('DROP TABLE user_league');
    }
}
