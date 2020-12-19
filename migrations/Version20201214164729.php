<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201214164729 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event ADD team_per_turnament INT DEFAULT NULL');
        $this->addSql('ALTER TABLE group_stage ADD player_per_group_stage INT DEFAULT NULL');
        $this->addSql('ALTER TABLE player CHANGE level level enum(\'Pro\', \'Elite\', \'N2\', \'N3\', \'Régional\', \'Départemental\',\'Loisir\')');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP team_per_turnament');
        $this->addSql('ALTER TABLE group_stage DROP player_per_group_stage');
        $this->addSql('ALTER TABLE player CHANGE level level VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
