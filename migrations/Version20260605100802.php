<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260605100802 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE score_entry ADD player_id INT NOT NULL');
        $this->addSql('ALTER TABLE score_entry ADD CONSTRAINT FK_926D51F899E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
        $this->addSql('CREATE INDEX IDX_926D51F899E6F5DF ON score_entry (player_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE score_entry DROP FOREIGN KEY FK_926D51F899E6F5DF');
        $this->addSql('DROP INDEX IDX_926D51F899E6F5DF ON score_entry');
        $this->addSql('ALTER TABLE score_entry DROP player_id');
    }
}
