<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210419075557 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE memo_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE memo (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE "user" ADD user_inform_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD user_informed_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D64911D2583F FOREIGN KEY (user_inform_id) REFERENCES memo (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649B392F7F3 FOREIGN KEY (user_informed_id) REFERENCES memo (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8D93D64911D2583F ON "user" (user_inform_id)');
        $this->addSql('CREATE INDEX IDX_8D93D649B392F7F3 ON "user" (user_informed_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D64911D2583F');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649B392F7F3');
        $this->addSql('DROP SEQUENCE memo_id_seq CASCADE');
        $this->addSql('DROP TABLE memo');
        $this->addSql('DROP INDEX IDX_8D93D64911D2583F');
        $this->addSql('DROP INDEX IDX_8D93D649B392F7F3');
        $this->addSql('ALTER TABLE "user" DROP user_inform_id');
        $this->addSql('ALTER TABLE "user" DROP user_informed_id');
    }
}
