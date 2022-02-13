<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220213173029 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE liga (id INT AUTO_INCREMENT NOT NULL, deporte VARCHAR(255) NOT NULL, nombre_liga VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE liga_equipo (liga_id INT NOT NULL, equipo_id INT NOT NULL, INDEX IDX_983BBD17CF098064 (liga_id), INDEX IDX_983BBD1723BFBED (equipo_id), PRIMARY KEY(liga_id, equipo_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE liga_equipo ADD CONSTRAINT FK_983BBD17CF098064 FOREIGN KEY (liga_id) REFERENCES liga (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE liga_equipo ADD CONSTRAINT FK_983BBD1723BFBED FOREIGN KEY (equipo_id) REFERENCES equipo (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE liga_equipo DROP FOREIGN KEY FK_983BBD17CF098064');
        $this->addSql('DROP TABLE liga');
        $this->addSql('DROP TABLE liga_equipo');
        $this->addSql('ALTER TABLE equipo CHANGE deporte deporte VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE nombre nombre VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
