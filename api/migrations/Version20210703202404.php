<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210703202404 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('INSERT INTO game (id, name, slug) VALUES (1, "Mock game", "mock-game")');
        $this->addSql('INSERT INTO faction (id, name) VALUES (1, "Alliance"), (2, "Horde")');
        $this->addSql('INSERT INTO region (id, name) VALUES (1, "USA"), (2, "Europe")');
        $this->addSql('INSERT INTO event_type (id, name, is_active, sort, default_duration, default_slot_limit, game_id)
            VALUES
               (1, "Test event type 1", 1, 1, 120, 10, 1),
               (2, "Test event type 2", 1, 2, 90, 10, 1),
               (3, "Test event type 3", 1, 3, 60, 10, 1),
               (4, "Test event type 4", 1, 4, 30, 10, 1)');
        $this->addSql('INSERT INTO booster (id, name, is_active, sort, parent_id)
            VALUES
               (1, "Test booster 1", 1, 1, null),
               (2, "Test booster 2", 1, 2, null),
               (3, "Children booster 1", 1, 3, 1),
               (4, "Children booster 4", 1, 4, 1)');
        $this->addSql('INSERT INTO booster_game (booster_id, game_id)
            VALUES
               (1, 1),
               (2, 1),
               (3, 1),
               (4, 1)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
