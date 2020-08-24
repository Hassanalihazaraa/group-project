<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200824142823 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment_history (id INT AUTO_INCREMENT NOT NULL, ticket_id_id INT NOT NULL, created_by_id INT DEFAULT NULL, comments VARCHAR(1000) NOT NULL, is_private TINYINT(1) NOT NULL, from_manager TINYINT(1) NOT NULL, INDEX IDX_D4C9125B5774FDDC (ticket_id_id), INDEX IDX_D4C9125BB03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment_history ADD CONSTRAINT FK_D4C9125B5774FDDC FOREIGN KEY (ticket_id_id) REFERENCES tickets (id)');
        $this->addSql('ALTER TABLE comment_history ADD CONSTRAINT FK_D4C9125BB03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE tickets ADD is_escalated TINYINT(1) NOT NULL, ADD times_reopened INT NOT NULL, ADD priorities INT NOT NULL, ADD updated_message_time DATETIME DEFAULT NULL, CHANGE status status VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE comment_history');
        $this->addSql('ALTER TABLE tickets DROP is_escalated, DROP times_reopened, DROP priorities, DROP updated_message_time, CHANGE status status INT NOT NULL');
    }
}
