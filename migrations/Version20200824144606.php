<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200824144606 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment_history DROP FOREIGN KEY FK_D4C9125B5774FDDC');
        $this->addSql('DROP INDEX IDX_D4C9125B5774FDDC ON comment_history');
        $this->addSql('ALTER TABLE comment_history CHANGE ticket_id ticket_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment_history ADD CONSTRAINT FK_D4C9125B5774FDDC FOREIGN KEY (ticket_id_id) REFERENCES tickets (id)');
        $this->addSql('CREATE INDEX IDX_D4C9125B5774FDDC ON comment_history (ticket_id_id)');
        $this->addSql('ALTER TABLE tickets ADD title VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment_history DROP FOREIGN KEY FK_D4C9125B5774FDDC');
        $this->addSql('DROP INDEX IDX_D4C9125B5774FDDC ON comment_history');
        $this->addSql('ALTER TABLE comment_history CHANGE ticket_id_id ticket_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment_history ADD CONSTRAINT FK_D4C9125B5774FDDC FOREIGN KEY (ticket_id) REFERENCES tickets (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_D4C9125B5774FDDC ON comment_history (ticket_id)');
        $this->addSql('ALTER TABLE tickets DROP title');
    }
}
