<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200824151309 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment_history (id INT AUTO_INCREMENT NOT NULL, ticket_id_id INT NOT NULL, created_by_id INT DEFAULT NULL, comments VARCHAR(1000) NOT NULL, is_private TINYINT(1) NOT NULL, from_manager TINYINT(1) NOT NULL, INDEX IDX_D4C9125B5774FDDC (ticket_id_id), INDEX IDX_D4C9125BB03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tickets (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, handling_agent_id INT DEFAULT NULL, message VARCHAR(1000) NOT NULL, status VARCHAR(255) NOT NULL, creation_time DATETIME NOT NULL, is_escalated TINYINT(1) NOT NULL, times_reopened INT NOT NULL, priorities INT NOT NULL, updated_message_time DATETIME DEFAULT NULL, title VARCHAR(255) NOT NULL, INDEX IDX_54469DF4B03A8386 (created_by_id), INDEX IDX_54469DF41DF91F51 (handling_agent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment_history ADD CONSTRAINT FK_D4C9125B5774FDDC FOREIGN KEY (ticket_id_id) REFERENCES tickets (id)');
        $this->addSql('ALTER TABLE comment_history ADD CONSTRAINT FK_D4C9125BB03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE tickets ADD CONSTRAINT FK_54469DF4B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE tickets ADD CONSTRAINT FK_54469DF41DF91F51 FOREIGN KEY (handling_agent_id) REFERENCES users (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment_history DROP FOREIGN KEY FK_D4C9125B5774FDDC');
        $this->addSql('ALTER TABLE comment_history DROP FOREIGN KEY FK_D4C9125BB03A8386');
        $this->addSql('ALTER TABLE tickets DROP FOREIGN KEY FK_54469DF4B03A8386');
        $this->addSql('ALTER TABLE tickets DROP FOREIGN KEY FK_54469DF41DF91F51');
        $this->addSql('DROP TABLE comment_history');
        $this->addSql('DROP TABLE tickets');
        $this->addSql('DROP TABLE users');
    }
}
