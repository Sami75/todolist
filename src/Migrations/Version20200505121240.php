<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200505121240 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE "user" ADD user_todolist_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D64940DADBF FOREIGN KEY (user_todolist_id) REFERENCES to_do_list (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64940DADBF ON "user" (user_todolist_id)');
        $this->addSql('ALTER TABLE item ADD item_todolist_id INT NOT NULL');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E4D7E3C1C FOREIGN KEY (item_todolist_id) REFERENCES to_do_list (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_1F1B251E4D7E3C1C ON item (item_todolist_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D64940DADBF');
        $this->addSql('DROP INDEX UNIQ_8D93D64940DADBF');
        $this->addSql('ALTER TABLE "user" DROP user_todolist_id');
        $this->addSql('ALTER TABLE item DROP CONSTRAINT FK_1F1B251E4D7E3C1C');
        $this->addSql('DROP INDEX IDX_1F1B251E4D7E3C1C');
        $this->addSql('ALTER TABLE item DROP item_todolist_id');
    }
}
