<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250515071513 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, fio_id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE cogc_program (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(255) DEFAULT NULL, started_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
            , ended_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
            , status VARCHAR(255) NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE company (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, fio_id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(5) NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE material (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER NOT NULL, fio_id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, ticker VARCHAR(255) NOT NULL, mass DOUBLE PRECISION NOT NULL, volume DOUBLE PRECISION NOT NULL, CONSTRAINT FK_7CBE759512469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_7CBE759512469DE2 ON material (category_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE planet (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, cogc_program_id INTEGER DEFAULT NULL, fio_id VARCHAR(255) NOT NULL, natural_id VARCHAR(255) NOT NULL, name VARCHAR(255) DEFAULT NULL, gravity DOUBLE PRECISION NOT NULL, pressure DOUBLE PRECISION NOT NULL, temperature DOUBLE PRECISION NOT NULL, has_surface BOOLEAN NOT NULL, fertility DOUBLE PRECISION NOT NULL, planetary_infrastructure CLOB DEFAULT NULL --(DC2Type:simple_array)
            , CONSTRAINT FK_68136AA544E133C2 FOREIGN KEY (cogc_program_id) REFERENCES cogc_program (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_68136AA5108A3AF ON planet (fio_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_68136AA5FBBE0F1A ON planet (natural_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_68136AA544E133C2 ON planet (cogc_program_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE resource (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, material_id INTEGER NOT NULL, planet_id INTEGER NOT NULL, type VARCHAR(255) NOT NULL, factor DOUBLE PRECISION NOT NULL, CONSTRAINT FK_BC91F416E308AC6F FOREIGN KEY (material_id) REFERENCES material (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_BC91F416A25E9820 FOREIGN KEY (planet_id) REFERENCES planet (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_BC91F416E308AC6F ON resource (material_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_BC91F416A25E9820 ON resource (planet_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE site (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, planet_id INTEGER NOT NULL, owner_id INTEGER DEFAULT NULL, plot_id VARCHAR(255) NOT NULL, site_id VARCHAR(255) NOT NULL, plot_number INTEGER NOT NULL, CONSTRAINT FK_694309E4A25E9820 FOREIGN KEY (planet_id) REFERENCES planet (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_694309E47E3C61F9 FOREIGN KEY (owner_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_694309E4A25E9820 ON site (planet_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_694309E47E3C61F9 ON site (owner_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE workforce (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(255) NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE workforce_need (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, workforce_id INTEGER NOT NULL, material_id INTEGER NOT NULL, amount DOUBLE PRECISION NOT NULL, CONSTRAINT FK_E2DCEE42A25BA942 FOREIGN KEY (workforce_id) REFERENCES workforce (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_E2DCEE42E308AC6F FOREIGN KEY (material_id) REFERENCES material (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E2DCEE42A25BA942 ON workforce_need (workforce_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E2DCEE42E308AC6F ON workforce_need (material_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE category
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE cogc_program
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE company
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE material
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE planet
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE resource
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE site
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE workforce
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE workforce_need
        SQL);
    }
}
