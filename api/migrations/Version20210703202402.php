<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210703202402 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE booster (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, parent_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, is_active BOOLEAN NOT NULL, sort INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_EF769FAD727ACA70 ON booster (parent_id)');
        $this->addSql('CREATE TABLE booster_game (booster_id INTEGER NOT NULL, game_id INTEGER NOT NULL, PRIMARY KEY(booster_id, game_id))');
        $this->addSql('CREATE INDEX IDX_9A001083F85E4930 ON booster_game (booster_id)');
        $this->addSql('CREATE INDEX IDX_9A001083E48FD905 ON booster_game (game_id)');
        $this->addSql('CREATE TABLE event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, booster_id INTEGER NOT NULL, event_type_id INTEGER NOT NULL, faction_id INTEGER NOT NULL, region_id INTEGER NOT NULL, game_id INTEGER NOT NULL, datetime DATETIME NOT NULL, slots_limit INTEGER NOT NULL, custom_duration INTEGER DEFAULT NULL, is_locked BOOLEAN NOT NULL)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7F85E4930 ON event (booster_id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7401B253C ON event (event_type_id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA74448F8DA ON event (faction_id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA798260155 ON event (region_id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7E48FD905 ON event (game_id)');
        $this->addSql('CREATE TABLE event_type (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, game_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, is_active BOOLEAN NOT NULL, sort INTEGER NOT NULL, default_duration INTEGER DEFAULT NULL, default_slot_limit INTEGER DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_93151B82E48FD905 ON event_type (game_id)');
        $this->addSql('CREATE TABLE faction (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE game (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE region (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE booster');
        $this->addSql('DROP TABLE booster_game');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_type');
        $this->addSql('DROP TABLE faction');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE region');
    }
}
