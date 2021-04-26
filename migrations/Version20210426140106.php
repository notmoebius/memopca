<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210426140106 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE memo DROP CONSTRAINT fk_ab4a902a1132d5db');
        $this->addSql('ALTER TABLE memo DROP CONSTRAINT fk_ab4a902a1a893e66');
        $this->addSql('DROP INDEX idx_ab4a902a1a893e66');
        $this->addSql('DROP INDEX idx_ab4a902a1132d5db');
        $this->addSql('ALTER TABLE memo ADD inform1_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE memo ADD informed1_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE memo DROP inform_id');
        $this->addSql('ALTER TABLE memo DROP informed_id');
        $this->addSql('ALTER TABLE memo ADD CONSTRAINT FK_AB4A902AC77C141A FOREIGN KEY (inform1_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE memo ADD CONSTRAINT FK_AB4A902A72A760C0 FOREIGN KEY (informed1_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_AB4A902AC77C141A ON memo (inform1_id)');
        $this->addSql('CREATE INDEX IDX_AB4A902A72A760C0 ON memo (informed1_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE memo DROP CONSTRAINT FK_AB4A902AC77C141A');
        $this->addSql('ALTER TABLE memo DROP CONSTRAINT FK_AB4A902A72A760C0');
        $this->addSql('DROP INDEX IDX_AB4A902AC77C141A');
        $this->addSql('DROP INDEX IDX_AB4A902A72A760C0');
        $this->addSql('ALTER TABLE memo ADD inform_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE memo ADD informed_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE memo DROP inform1_id');
        $this->addSql('ALTER TABLE memo DROP informed1_id');
        $this->addSql('ALTER TABLE memo ADD CONSTRAINT fk_ab4a902a1132d5db FOREIGN KEY (inform_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE memo ADD CONSTRAINT fk_ab4a902a1a893e66 FOREIGN KEY (informed_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_ab4a902a1a893e66 ON memo (informed_id)');
        $this->addSql('CREATE INDEX idx_ab4a902a1132d5db ON memo (inform_id)');
    }
}
