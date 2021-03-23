<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210323154722 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE box (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', name VARCHAR(255) NOT NULL, hash VARCHAR(255) NOT NULL, start_datetime DATETIME DEFAULT NULL, end_datetime DATETIME DEFAULT NULL, is_open TINYINT(1) DEFAULT \'0\' NOT NULL, is_public TINYINT(1) DEFAULT \'0\' NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, UNIQUE INDEX UNIQ_8A9483AD1B862B8 (hash), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE suggestion (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', box_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', suggestion_type_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', value VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_DD80F31BD8177B3F (box_id), INDEX IDX_DD80F31BAA96CFE7 (suggestion_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE suggestion_type (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', box_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_87BB983AD8177B3F (box_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE suggestion ADD CONSTRAINT FK_DD80F31BD8177B3F FOREIGN KEY (box_id) REFERENCES box (id)');
        $this->addSql('ALTER TABLE suggestion ADD CONSTRAINT FK_DD80F31BAA96CFE7 FOREIGN KEY (suggestion_type_id) REFERENCES suggestion_type (id)');
        $this->addSql('ALTER TABLE suggestion_type ADD CONSTRAINT FK_87BB983AD8177B3F FOREIGN KEY (box_id) REFERENCES box (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE suggestion DROP FOREIGN KEY FK_DD80F31BD8177B3F');
        $this->addSql('ALTER TABLE suggestion_type DROP FOREIGN KEY FK_87BB983AD8177B3F');
        $this->addSql('ALTER TABLE suggestion DROP FOREIGN KEY FK_DD80F31BAA96CFE7');
        $this->addSql('DROP TABLE box');
        $this->addSql('DROP TABLE suggestion');
        $this->addSql('DROP TABLE suggestion_type');
    }
}
