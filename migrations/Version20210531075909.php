<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210531075909 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE crisis_room_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE type_crisis_room_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE crisis_room (id INT NOT NULL, type_crisis_room_id INT NOT NULL, organization_id INT NOT NULL, reference VARCHAR(255) NOT NULL, phonenumber VARCHAR(20) DEFAULT NULL, faxnumber VARCHAR(30) DEFAULT NULL, address1 VARCHAR(255) NOT NULL, adress2 VARCHAR(255) NOT NULL, address3 VARCHAR(255) DEFAULT NULL, plan VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F8121538DEF6CF28 ON crisis_room (type_crisis_room_id)');
        $this->addSql('CREATE INDEX IDX_F812153832C8A3DE ON crisis_room (organization_id)');
        $this->addSql('CREATE TABLE type_crisis_room (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE crisis_room ADD CONSTRAINT FK_F8121538DEF6CF28 FOREIGN KEY (type_crisis_room_id) REFERENCES type_crisis_room (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE crisis_room ADD CONSTRAINT FK_F812153832C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE crisis_room DROP CONSTRAINT FK_F8121538DEF6CF28');
        $this->addSql('DROP SEQUENCE crisis_room_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE type_crisis_room_id_seq CASCADE');
        $this->addSql('DROP TABLE crisis_room');
        $this->addSql('DROP TABLE type_crisis_room');
    }
}
