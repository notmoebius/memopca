<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210413070942 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE directory_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE directory (id INT NOT NULL, name VARCHAR(30) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE "user" ADD directory_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D6492C94069F FOREIGN KEY (directory_id) REFERENCES directory (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8D93D6492C94069F ON "user" (directory_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D6492C94069F');
        $this->addSql('DROP SEQUENCE directory_id_seq CASCADE');
        $this->addSql('DROP TABLE directory');
        $this->addSql('DROP INDEX IDX_8D93D6492C94069F');
        $this->addSql('ALTER TABLE "user" DROP directory_id');
    }
}
