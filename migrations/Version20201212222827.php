<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201212222827 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, organizer_id INT NOT NULL, name VARCHAR(255) NOT NULL, date DATETIME NOT NULL, location VARCHAR(255) NOT NULL, player_per_team INT NOT NULL, sport VARCHAR(255) NOT NULL, INDEX IDX_3BAE0AA7876C4DDA (organizer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE group_stage (id INT AUTO_INCREMENT NOT NULL, round_id INT NOT NULL, round_double_id INT DEFAULT NULL, set_per_group_stage INT NOT NULL, set_points INT NOT NULL, INDEX IDX_9250B80EA6005CA0 (round_id), INDEX IDX_9250B80E57F8A9C6 (round_double_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matches (id INT AUTO_INCREMENT NOT NULL, team_a_id INT NOT NULL, team_b_id INT NOT NULL, score_team_a INT DEFAULT NULL, score_team_b INT DEFAULT NULL, set_index INT NOT NULL, group_stage_index INT NOT NULL, INDEX IDX_62615BAEA3FA723 (team_a_id), INDEX IDX_62615BAF88A08CD (team_b_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organizer (id INT NOT NULL, structure VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player (id INT NOT NULL, team_id INT DEFAULT NULL, level enum(\'Pro\', \'Elite\', \'N2\', \'N3\', \'Régional\', \'Départemental\',\'Loisir\'), INDEX IDX_98197A65296CD8AE (team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE round (id INT AUTO_INCREMENT NOT NULL, turnament_id INT NOT NULL, loser_bracket_round_id INT DEFAULT NULL, player_per_round_stage INT NOT NULL, loser_bracket TINYINT(1) NOT NULL, last_round TINYINT(1) NOT NULL, double_elimination TINYINT(1) NOT NULL, INDEX IDX_C5EEEA3440B77CEA (turnament_id), UNIQUE INDEX UNIQ_C5EEEA34E9AA2E6 (loser_bracket_round_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, group_stage_id INT DEFAULT NULL, turnament_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, level INT NOT NULL, INDEX IDX_C4E0A61F2BA89DEC (group_stage_id), INDEX IDX_C4E0A61F40B77CEA (turnament_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE turnament (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, Event_id INT DEFAULT NULL, INDEX IDX_69EB3D4F88818ADD (Event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7876C4DDA FOREIGN KEY (organizer_id) REFERENCES organizer (id)');
        $this->addSql('ALTER TABLE group_stage ADD CONSTRAINT FK_9250B80EA6005CA0 FOREIGN KEY (round_id) REFERENCES round (id)');
        $this->addSql('ALTER TABLE group_stage ADD CONSTRAINT FK_9250B80E57F8A9C6 FOREIGN KEY (round_double_id) REFERENCES round (id)');
        $this->addSql('ALTER TABLE matches ADD CONSTRAINT FK_62615BAEA3FA723 FOREIGN KEY (team_a_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE matches ADD CONSTRAINT FK_62615BAF88A08CD FOREIGN KEY (team_b_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE organizer ADD CONSTRAINT FK_99D47173BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A65296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A65BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE round ADD CONSTRAINT FK_C5EEEA3440B77CEA FOREIGN KEY (turnament_id) REFERENCES turnament (id)');
        $this->addSql('ALTER TABLE round ADD CONSTRAINT FK_C5EEEA34E9AA2E6 FOREIGN KEY (loser_bracket_round_id) REFERENCES round (id)');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F2BA89DEC FOREIGN KEY (group_stage_id) REFERENCES group_stage (id)');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F40B77CEA FOREIGN KEY (turnament_id) REFERENCES turnament (id)');
        $this->addSql('ALTER TABLE turnament ADD CONSTRAINT FK_69EB3D4F88818ADD FOREIGN KEY (Event_id) REFERENCES event (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE turnament DROP FOREIGN KEY FK_69EB3D4F88818ADD');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61F2BA89DEC');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7876C4DDA');
        $this->addSql('ALTER TABLE group_stage DROP FOREIGN KEY FK_9250B80EA6005CA0');
        $this->addSql('ALTER TABLE group_stage DROP FOREIGN KEY FK_9250B80E57F8A9C6');
        $this->addSql('ALTER TABLE round DROP FOREIGN KEY FK_C5EEEA34E9AA2E6');
        $this->addSql('ALTER TABLE matches DROP FOREIGN KEY FK_62615BAEA3FA723');
        $this->addSql('ALTER TABLE matches DROP FOREIGN KEY FK_62615BAF88A08CD');
        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_98197A65296CD8AE');
        $this->addSql('ALTER TABLE round DROP FOREIGN KEY FK_C5EEEA3440B77CEA');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61F40B77CEA');
        $this->addSql('ALTER TABLE organizer DROP FOREIGN KEY FK_99D47173BF396750');
        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_98197A65BF396750');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE group_stage');
        $this->addSql('DROP TABLE matches');
        $this->addSql('DROP TABLE organizer');
        $this->addSql('DROP TABLE player');
        $this->addSql('DROP TABLE round');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP TABLE turnament');
        $this->addSql('DROP TABLE user');
    }
}
