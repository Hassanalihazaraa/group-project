<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200826075343 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_D4C9125B5774FDDC ON comment_history');
        $this->addSql('ALTER TABLE comment_history CHANGE ticket_id_id ticket_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment_history ADD CONSTRAINT FK_D4C9125B700047D2 FOREIGN KEY (ticket_id) REFERENCES ticket (id)');
        $this->addSql('ALTER TABLE comment_history ADD CONSTRAINT FK_D4C9125BB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D4C9125B700047D2 ON comment_history (ticket_id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment_history DROP FOREIGN KEY FK_D4C9125B700047D2');
        $this->addSql('ALTER TABLE comment_history DROP FOREIGN KEY FK_D4C9125BB03A8386');
        $this->addSql('DROP INDEX IDX_D4C9125B700047D2 ON comment_history');
        $this->addSql('ALTER TABLE comment_history CHANGE ticket_id ticket_id_id INT NOT NULL');
        $this->addSql('CREATE INDEX IDX_D4C9125B5774FDDC ON comment_history (ticket_id_id)');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
    }
}
