<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200505120610 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE to_do_list_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE to_do_list (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE item DROP CONSTRAINT fk_1f1b251eac33f5c1');
        $this->addSql('DROP INDEX idx_1f1b251eac33f5c1');
        $this->addSql('ALTER TABLE item DROP item_user_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE to_do_list_id_seq CASCADE');
        $this->addSql('DROP TABLE to_do_list');
        $this->addSql('ALTER TABLE item ADD item_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT fk_1f1b251eac33f5c1 FOREIGN KEY (item_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_1f1b251eac33f5c1 ON item (item_user_id)');
    }
}
