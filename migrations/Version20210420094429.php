<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210420094429 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE login ADD organization_id INT NOT NULL');
        $this->addSql('ALTER TABLE login ADD CONSTRAINT FK_AA08CB1032C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_AA08CB1032C8A3DE ON login (organization_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE login DROP CONSTRAINT FK_AA08CB1032C8A3DE');
        $this->addSql('DROP INDEX IDX_AA08CB1032C8A3DE');
        $this->addSql('ALTER TABLE login DROP organization_id');
    }
}
