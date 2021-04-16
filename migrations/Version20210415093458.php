<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210415093458 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE warn_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE warn (id INT NOT NULL, previens_id INT NOT NULL, prevenu_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_29BAB1059B4DA7E5 ON warn (previens_id)');
        $this->addSql('CREATE INDEX IDX_29BAB10567FF563A ON warn (prevenu_id)');
        $this->addSql('ALTER TABLE warn ADD CONSTRAINT FK_29BAB1059B4DA7E5 FOREIGN KEY (previens_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE warn ADD CONSTRAINT FK_29BAB10567FF563A FOREIGN KEY (prevenu_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE warn_id_seq CASCADE');
        $this->addSql('DROP TABLE warn');
    }
}
