<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210419072807 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER INDEX idx_29bab1059b4da7e5 RENAME TO IDX_29BAB1051132D5DB');
        $this->addSql('ALTER INDEX idx_29bab10567ff563a RENAME TO IDX_29BAB1051A893E66');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER INDEX idx_29bab1051a893e66 RENAME TO idx_29bab10567ff563a');
        $this->addSql('ALTER INDEX idx_29bab1051132d5db RENAME TO idx_29bab1059b4da7e5');
    }
}
