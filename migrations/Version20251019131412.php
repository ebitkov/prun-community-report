<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251019131412 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE building (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, expertise_id INTEGER DEFAULT NULL, fio_id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, ticker VARCHAR(255) NOT NULL, required_pioneers INTEGER NOT NULL, required_settlers INTEGER NOT NULL, required_technicians INTEGER NOT NULL, required_engineers INTEGER NOT NULL, required_scientists INTEGER NOT NULL, area_cost INTEGER NOT NULL, CONSTRAINT FK_E16F61D49D5B92F9 FOREIGN KEY (expertise_id) REFERENCES expertise (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_E16F61D49D5B92F9 ON building (expertise_id)');
        $this->addSql('CREATE TABLE building_cost (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, building_id INTEGER NOT NULL, material_id INTEGER NOT NULL, amount INTEGER NOT NULL, CONSTRAINT FK_BE02E3A84D2A7E12 FOREIGN KEY (building_id) REFERENCES building (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_BE02E3A8E308AC6F FOREIGN KEY (material_id) REFERENCES material (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_BE02E3A84D2A7E12 ON building_cost (building_id)');
        $this->addSql('CREATE INDEX IDX_BE02E3A8E308AC6F ON building_cost (material_id)');
        $this->addSql('CREATE TABLE building_recipe (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, building_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, standard_name VARCHAR(255) NOT NULL, duration_ms INTEGER NOT NULL, CONSTRAINT FK_CA3E6BDC4D2A7E12 FOREIGN KEY (building_id) REFERENCES building (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_CA3E6BDC4D2A7E12 ON building_recipe (building_id)');
        $this->addSql('CREATE TABLE building_recipe_ingredient (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, material_id INTEGER NOT NULL, recipe_input_id INTEGER DEFAULT NULL, recipe_output_id INTEGER DEFAULT NULL, amount INTEGER NOT NULL, CONSTRAINT FK_29334D4CE308AC6F FOREIGN KEY (material_id) REFERENCES material (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_29334D4C40D2BF48 FOREIGN KEY (recipe_input_id) REFERENCES building_recipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_29334D4CC9C85666 FOREIGN KEY (recipe_output_id) REFERENCES building_recipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_29334D4CE308AC6F ON building_recipe_ingredient (material_id)');
        $this->addSql('CREATE INDEX IDX_29334D4C40D2BF48 ON building_recipe_ingredient (recipe_input_id)');
        $this->addSql('CREATE INDEX IDX_29334D4CC9C85666 ON building_recipe_ingredient (recipe_output_id)');
        $this->addSql('CREATE TABLE category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, fio_id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE cogc_program (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(255) DEFAULT NULL, started_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , ended_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , status VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE company (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, fio_id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(5) NOT NULL)');
        $this->addSql('CREATE TABLE exchange_station (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, system_id INTEGER NOT NULL, fio_id VARCHAR(255) NOT NULL, natural_id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, CONSTRAINT FK_D8F32E30D0952FA5 FOREIGN KEY (system_id) REFERENCES system (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_D8F32E30D0952FA5 ON exchange_station (system_id)');
        $this->addSql('CREATE TABLE expertise (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE infrastructure (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, report_id INTEGER NOT NULL, type VARCHAR(255) NOT NULL, ticker VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, level INTEGER NOT NULL, active_level INTEGER NOT NULL, current_level INTEGER NOT NULL, CONSTRAINT FK_D129B1904BD2A4C0 FOREIGN KEY (report_id) REFERENCES infrastructure_report (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_D129B1904BD2A4C0 ON infrastructure (report_id)');
        $this->addSql('CREATE TABLE infrastructure_report (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, planet_id INTEGER NOT NULL, simulation_period INTEGER NOT NULL, started_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , is_explorers_grace_enabled BOOLEAN NOT NULL, CONSTRAINT FK_517BB562A25E9820 FOREIGN KEY (planet_id) REFERENCES planet (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_517BB562A25E9820 ON infrastructure_report (planet_id)');
        $this->addSql('CREATE TABLE material (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER NOT NULL, fio_id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, ticker VARCHAR(255) NOT NULL, mass DOUBLE PRECISION NOT NULL, volume DOUBLE PRECISION NOT NULL, CONSTRAINT FK_7CBE759512469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_7CBE759512469DE2 ON material (category_id)');
        $this->addSql('CREATE TABLE planet (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, cogc_program_id INTEGER DEFAULT NULL, system_id INTEGER NOT NULL, fio_id VARCHAR(255) NOT NULL, natural_id VARCHAR(255) NOT NULL, name VARCHAR(255) DEFAULT NULL, gravity DOUBLE PRECISION NOT NULL, pressure DOUBLE PRECISION NOT NULL, temperature DOUBLE PRECISION NOT NULL, has_surface BOOLEAN NOT NULL, fertility DOUBLE PRECISION NOT NULL, planetary_infrastructure CLOB DEFAULT NULL --(DC2Type:simple_array)
        , jumps_to_ant INTEGER DEFAULT NULL, CONSTRAINT FK_68136AA544E133C2 FOREIGN KEY (cogc_program_id) REFERENCES cogc_program (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_68136AA5D0952FA5 FOREIGN KEY (system_id) REFERENCES system (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_68136AA5108A3AF ON planet (fio_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_68136AA5FBBE0F1A ON planet (natural_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_68136AA544E133C2 ON planet (cogc_program_id)');
        $this->addSql('CREATE INDEX IDX_68136AA5D0952FA5 ON planet (system_id)');
        $this->addSql('CREATE TABLE population (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, report_id INTEGER NOT NULL, type VARCHAR(255) NOT NULL, amount INTEGER NOT NULL, difference INTEGER NOT NULL, average_happiness DOUBLE PRECISION NOT NULL, unemployment_rate DOUBLE PRECISION NOT NULL, open_jobs INTEGER NOT NULL, CONSTRAINT FK_B449A0084BD2A4C0 FOREIGN KEY (report_id) REFERENCES infrastructure_report (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_B449A0084BD2A4C0 ON population (report_id)');
        $this->addSql('CREATE TABLE resource (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, material_id INTEGER NOT NULL, planet_id INTEGER NOT NULL, type VARCHAR(255) NOT NULL, factor DOUBLE PRECISION NOT NULL, CONSTRAINT FK_BC91F416E308AC6F FOREIGN KEY (material_id) REFERENCES material (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_BC91F416A25E9820 FOREIGN KEY (planet_id) REFERENCES planet (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_BC91F416E308AC6F ON resource (material_id)');
        $this->addSql('CREATE INDEX IDX_BC91F416A25E9820 ON resource (planet_id)');
        $this->addSql('CREATE TABLE site (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, planet_id INTEGER NOT NULL, owner_id INTEGER DEFAULT NULL, plot_id VARCHAR(255) NOT NULL, site_id VARCHAR(255) NOT NULL, plot_number INTEGER NOT NULL, CONSTRAINT FK_694309E4A25E9820 FOREIGN KEY (planet_id) REFERENCES planet (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_694309E47E3C61F9 FOREIGN KEY (owner_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_694309E4A25E9820 ON site (planet_id)');
        $this->addSql('CREATE INDEX IDX_694309E47E3C61F9 ON site (owner_id)');
        $this->addSql('CREATE TABLE system (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, fio_id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, natural_id VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE workforce (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE workforce_need (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, workforce_id INTEGER NOT NULL, material_id INTEGER NOT NULL, amount DOUBLE PRECISION NOT NULL, CONSTRAINT FK_E2DCEE42A25BA942 FOREIGN KEY (workforce_id) REFERENCES workforce (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_E2DCEE42E308AC6F FOREIGN KEY (material_id) REFERENCES material (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_E2DCEE42A25BA942 ON workforce_need (workforce_id)');
        $this->addSql('CREATE INDEX IDX_E2DCEE42E308AC6F ON workforce_need (material_id)');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , available_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , delivered_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE building');
        $this->addSql('DROP TABLE building_cost');
        $this->addSql('DROP TABLE building_recipe');
        $this->addSql('DROP TABLE building_recipe_ingredient');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE cogc_program');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE exchange_station');
        $this->addSql('DROP TABLE expertise');
        $this->addSql('DROP TABLE infrastructure');
        $this->addSql('DROP TABLE infrastructure_report');
        $this->addSql('DROP TABLE material');
        $this->addSql('DROP TABLE planet');
        $this->addSql('DROP TABLE population');
        $this->addSql('DROP TABLE resource');
        $this->addSql('DROP TABLE site');
        $this->addSql('DROP TABLE system');
        $this->addSql('DROP TABLE workforce');
        $this->addSql('DROP TABLE workforce_need');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
