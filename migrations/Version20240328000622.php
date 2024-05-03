<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240328000622 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE note');
        $this->addSql('DROP TABLE professor');
        $this->addSql('DROP INDEX email ON student');
        $this->addSql('ALTER TABLE student ADD first_name VARCHAR(255) NOT NULL, ADD last_name VARCHAR(255) NOT NULL, DROP firstName, DROP lastName, CHANGE email email VARCHAR(255) NOT NULL, CHANGE password password VARCHAR(255) NOT NULL, CHANGE role role VARCHAR(255) NOT NULL, CHANGE birthDate birth_date DATE NOT NULL, CHANGE phoneNumber phone_number INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, password VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, firstName VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, lastName VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, birthDate DATE NOT NULL, phoneNumber INT NOT NULL, cin INT NOT NULL, role VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE note (id INT AUTO_INCREMENT NOT NULL, noteContent TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, studentId INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE professor (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, password VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, firstName VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, lastName VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, birthDate DATE NOT NULL, phoneNumber INT NOT NULL, cin INT NOT NULL, role VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE student ADD firstName VARCHAR(200) NOT NULL, ADD lastName VARCHAR(200) NOT NULL, DROP first_name, DROP last_name, CHANGE email email VARCHAR(200) NOT NULL, CHANGE password password VARCHAR(200) NOT NULL, CHANGE role role VARCHAR(50) NOT NULL, CHANGE birth_date birthDate DATE NOT NULL, CHANGE phone_number phoneNumber INT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX email ON student (email)');
    }
}
