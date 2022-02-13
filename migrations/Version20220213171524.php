<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220213171524 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE equipo (id INT AUTO_INCREMENT NOT NULL, deporte VARCHAR(255) NOT NULL, capitan TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE solicita (id INT AUTO_INCREMENT NOT NULL, id_usuario_id INT NOT NULL, id_equipo_id INT NOT NULL, aceptado TINYINT(1) NOT NULL, fecha_solicitud DATETIME NOT NULL, INDEX IDX_CE64EA757EB2C349 (id_usuario_id), INDEX IDX_CE64EA75820E47CA (id_equipo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE solicita ADD CONSTRAINT FK_CE64EA757EB2C349 FOREIGN KEY (id_usuario_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE solicita ADD CONSTRAINT FK_CE64EA75820E47CA FOREIGN KEY (id_equipo_id) REFERENCES equipo (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE solicita DROP FOREIGN KEY FK_CE64EA75820E47CA');
        $this->addSql('DROP TABLE equipo');
        $this->addSql('DROP TABLE solicita');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE nombre nombre VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
