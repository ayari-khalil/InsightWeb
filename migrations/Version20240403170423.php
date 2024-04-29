<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240403170423 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE customer_service (idsup INT AUTO_INCREMENT NOT NULL, fullname VARCHAR(255) NOT NULL, emailsup VARCHAR(255) NOT NULL, pnsup INT NOT NULL, issue VARCHAR(255) NOT NULL, subject VARCHAR(255) NOT NULL, message VARCHAR(255) NOT NULL, stater INT NOT NULL, PRIMARY KEY(idsup)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE responses (idres INT AUTO_INCREMENT NOT NULL, idsup INT NOT NULL, emailsup VARCHAR(255) NOT NULL, reponse VARCHAR(255) NOT NULL, dater DATE NOT NULL, PRIMARY KEY(idres)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE reclamations');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, fullname VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, emailrec VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, pnrec INT NOT NULL, issue VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, subject VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, message VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, stater INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reclamations (idrec INT AUTO_INCREMENT NOT NULL, fullname VARCHAR(250) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, emailrec VARCHAR(250) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, pnrec INT NOT NULL, issue VARCHAR(250) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, subject VARCHAR(250) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, message VARCHAR(250) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, stater INT DEFAULT 0 NOT NULL, PRIMARY KEY(idrec)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE customer_service');
        $this->addSql('DROP TABLE responses');
    }
}
