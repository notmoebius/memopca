<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210503133251 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE memo ADD login_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE memo ADD CONSTRAINT FK_AB4A902A5CB2E05D FOREIGN KEY (login_id) REFERENCES login (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_AB4A902A5CB2E05D ON memo (login_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE memo DROP CONSTRAINT FK_AB4A902A5CB2E05D');
        $this->addSql('DROP INDEX IDX_AB4A902A5CB2E05D');
        $this->addSql('ALTER TABLE memo DROP login_id');
    }
}
