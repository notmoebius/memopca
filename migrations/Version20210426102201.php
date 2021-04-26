<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210426102201 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE memo ADD inform2_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE memo ADD informed2_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE memo ADD CONSTRAINT FK_AB4A902AD5C9BBF4 FOREIGN KEY (inform2_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE memo ADD CONSTRAINT FK_AB4A902A6012CF2E FOREIGN KEY (informed2_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_AB4A902AD5C9BBF4 ON memo (inform2_id)');
        $this->addSql('CREATE INDEX IDX_AB4A902A6012CF2E ON memo (informed2_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE memo DROP CONSTRAINT FK_AB4A902AD5C9BBF4');
        $this->addSql('ALTER TABLE memo DROP CONSTRAINT FK_AB4A902A6012CF2E');
        $this->addSql('DROP INDEX IDX_AB4A902AD5C9BBF4');
        $this->addSql('DROP INDEX IDX_AB4A902A6012CF2E');
        $this->addSql('ALTER TABLE memo DROP inform2_id');
        $this->addSql('ALTER TABLE memo DROP informed2_id');
    }
}
