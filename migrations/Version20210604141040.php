<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210604141040 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ejercicio (id INT AUTO_INCREMENT NOT NULL, tipo_ejercicio_id INT NOT NULL, nombre VARCHAR(255) NOT NULL, ejecucion LONGTEXT DEFAULT NULL, foto VARCHAR(255) DEFAULT NULL, INDEX IDX_95ADCFF483DA547D (tipo_ejercicio_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ejercicio_rutina (id INT AUTO_INCREMENT NOT NULL, rutina_id INT NOT NULL, ejercicio_id INT NOT NULL, tiempo INT DEFAULT NULL, series INT DEFAULT NULL, repeticiones INT DEFAULT NULL, INDEX IDX_2F1FADCCD7A88FCB (rutina_id), INDEX IDX_2F1FADCC30890A7D (ejercicio_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE estadistica (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, peso DOUBLE PRECISION NOT NULL, altura DOUBLE PRECISION NOT NULL, imc DOUBLE PRECISION NOT NULL, INDEX IDX_DF3A8544DB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE red_social (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, facebook VARCHAR(255) DEFAULT NULL, instagram VARCHAR(255) DEFAULT NULL, twitter VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_465D8E03DB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rutina (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, nombre VARCHAR(255) NOT NULL, fecha_creacion DATE NOT NULL, INDEX IDX_A48AB255DB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tipo_ejercicio (id INT AUTO_INCREMENT NOT NULL, tipo VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usuario (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nombre VARCHAR(255) NOT NULL, apellidos VARCHAR(255) NOT NULL, fecha_nacimiento DATE NOT NULL, rol VARCHAR(255) NOT NULL, foto VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_2265B05DE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ejercicio ADD CONSTRAINT FK_95ADCFF483DA547D FOREIGN KEY (tipo_ejercicio_id) REFERENCES tipo_ejercicio (id)');
        $this->addSql('ALTER TABLE ejercicio_rutina ADD CONSTRAINT FK_2F1FADCCD7A88FCB FOREIGN KEY (rutina_id) REFERENCES rutina (id)');
        $this->addSql('ALTER TABLE ejercicio_rutina ADD CONSTRAINT FK_2F1FADCC30890A7D FOREIGN KEY (ejercicio_id) REFERENCES ejercicio (id)');
        $this->addSql('ALTER TABLE estadistica ADD CONSTRAINT FK_DF3A8544DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE red_social ADD CONSTRAINT FK_465D8E03DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE rutina ADD CONSTRAINT FK_A48AB255DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ejercicio_rutina DROP FOREIGN KEY FK_2F1FADCC30890A7D');
        $this->addSql('ALTER TABLE ejercicio_rutina DROP FOREIGN KEY FK_2F1FADCCD7A88FCB');
        $this->addSql('ALTER TABLE ejercicio DROP FOREIGN KEY FK_95ADCFF483DA547D');
        $this->addSql('ALTER TABLE estadistica DROP FOREIGN KEY FK_DF3A8544DB38439E');
        $this->addSql('ALTER TABLE red_social DROP FOREIGN KEY FK_465D8E03DB38439E');
        $this->addSql('ALTER TABLE rutina DROP FOREIGN KEY FK_A48AB255DB38439E');
        $this->addSql('DROP TABLE ejercicio');
        $this->addSql('DROP TABLE ejercicio_rutina');
        $this->addSql('DROP TABLE estadistica');
        $this->addSql('DROP TABLE red_social');
        $this->addSql('DROP TABLE rutina');
        $this->addSql('DROP TABLE tipo_ejercicio');
        $this->addSql('DROP TABLE usuario');
    }
}
