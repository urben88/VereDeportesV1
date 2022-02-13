<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220213174846 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE campo (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partido (id INT AUTO_INCREMENT NOT NULL, id_liga_id INT NOT NULL, id_profesor_id INT NOT NULL, id_campo_id INT NOT NULL, equipo1 INT NOT NULL, equipo2 INT NOT NULL, fecha_partido DATETIME NOT NULL, resul_equipo1 INT DEFAULT NULL, resul_equipo2 INT DEFAULT NULL, INDEX IDX_4E79750B11942914 (id_liga_id), INDEX IDX_4E79750BFD391480 (id_profesor_id), INDEX IDX_4E79750BF1A1D4C9 (id_campo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reserva (id INT AUTO_INCREMENT NOT NULL, id_usuario_id INT DEFAULT NULL, id_campo_id INT NOT NULL, id_profesor INT NOT NULL, fecha_creacion DATETIME NOT NULL, fecha DATETIME NOT NULL, fecha_caduca DATETIME DEFAULT NULL, INDEX IDX_188D2E3B7EB2C349 (id_usuario_id), INDEX IDX_188D2E3BF1A1D4C9 (id_campo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE partido ADD CONSTRAINT FK_4E79750B11942914 FOREIGN KEY (id_liga_id) REFERENCES liga (id)');
        $this->addSql('ALTER TABLE partido ADD CONSTRAINT FK_4E79750BFD391480 FOREIGN KEY (id_profesor_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE partido ADD CONSTRAINT FK_4E79750BF1A1D4C9 FOREIGN KEY (id_campo_id) REFERENCES campo (id)');
        $this->addSql('ALTER TABLE reserva ADD CONSTRAINT FK_188D2E3B7EB2C349 FOREIGN KEY (id_usuario_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reserva ADD CONSTRAINT FK_188D2E3BF1A1D4C9 FOREIGN KEY (id_campo_id) REFERENCES campo (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partido DROP FOREIGN KEY FK_4E79750BF1A1D4C9');
        $this->addSql('ALTER TABLE reserva DROP FOREIGN KEY FK_188D2E3BF1A1D4C9');
        $this->addSql('DROP TABLE campo');
        $this->addSql('DROP TABLE partido');
        $this->addSql('DROP TABLE reserva');
        $this->addSql('ALTER TABLE equipo CHANGE deporte deporte VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE liga CHANGE deporte deporte VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE nombre_liga nombre_liga VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE nombre nombre VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
