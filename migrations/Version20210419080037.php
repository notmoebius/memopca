<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210419080037 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE memo ADD inform_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE memo ADD informed_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE memo ADD CONSTRAINT FK_AB4A902A1132D5DB FOREIGN KEY (inform_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE memo ADD CONSTRAINT FK_AB4A902A1A893E66 FOREIGN KEY (informed_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_AB4A902A1132D5DB ON memo (inform_id)');
        $this->addSql('CREATE INDEX IDX_AB4A902A1A893E66 ON memo (informed_id)');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT fk_8d93d64911d2583f');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT fk_8d93d649b392f7f3');
        $this->addSql('DROP INDEX idx_8d93d64911d2583f');
        $this->addSql('DROP INDEX idx_8d93d649b392f7f3');
        $this->addSql('ALTER TABLE "user" DROP user_inform_id');
        $this->addSql('ALTER TABLE "user" DROP user_informed_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE memo DROP CONSTRAINT FK_AB4A902A1132D5DB');
        $this->addSql('ALTER TABLE memo DROP CONSTRAINT FK_AB4A902A1A893E66');
        $this->addSql('DROP INDEX IDX_AB4A902A1132D5DB');
        $this->addSql('DROP INDEX IDX_AB4A902A1A893E66');
        $this->addSql('ALTER TABLE memo DROP inform_id');
        $this->addSql('ALTER TABLE memo DROP informed_id');
        $this->addSql('ALTER TABLE "user" ADD user_inform_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD user_informed_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT fk_8d93d64911d2583f FOREIGN KEY (user_inform_id) REFERENCES memo (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT fk_8d93d649b392f7f3 FOREIGN KEY (user_informed_id) REFERENCES memo (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_8d93d64911d2583f ON "user" (user_inform_id)');
        $this->addSql('CREATE INDEX idx_8d93d649b392f7f3 ON "user" (user_informed_id)');
    }
}
