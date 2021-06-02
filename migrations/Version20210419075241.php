<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210419075241 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE warn_id_seq CASCADE');
        $this->addSql('DROP TABLE warn');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE warn_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE warn (id INT NOT NULL, inform_id INT DEFAULT NULL, informed_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_29bab1051a893e66 ON warn (informed_id)');
        $this->addSql('CREATE INDEX idx_29bab1051132d5db ON warn (inform_id)');
        $this->addSql('ALTER TABLE warn ADD CONSTRAINT fk_29bab1059b4da7e5 FOREIGN KEY (inform_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE warn ADD CONSTRAINT fk_29bab10567ff563a FOREIGN KEY (informed_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
