<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240512110436 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE score_game DROP FOREIGN KEY FK_933B0FA12EB0A51');
        $this->addSql('ALTER TABLE score_game DROP FOREIGN KEY FK_933B0FAE48FD905');
        $this->addSql('ALTER TABLE score_user DROP FOREIGN KEY FK_A78B573F12EB0A51');
        $this->addSql('ALTER TABLE score_user DROP FOREIGN KEY FK_A78B573FA76ED395');
        $this->addSql('DROP TABLE score_game');
        $this->addSql('DROP TABLE score_user');
        $this->addSql('ALTER TABLE score ADD user_id INT NOT NULL, ADD game_id INT NOT NULL, CHANGE score score INT NOT NULL');
        $this->addSql('ALTER TABLE score ADD CONSTRAINT FK_32993751A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE score ADD CONSTRAINT FK_32993751E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('CREATE INDEX IDX_32993751A76ED395 ON score (user_id)');
        $this->addSql('CREATE INDEX IDX_32993751E48FD905 ON score (game_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE score_game (score_id INT NOT NULL, game_id INT NOT NULL, INDEX IDX_933B0FAE48FD905 (game_id), INDEX IDX_933B0FA12EB0A51 (score_id), PRIMARY KEY(score_id, game_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE score_user (score_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_A78B573FA76ED395 (user_id), INDEX IDX_A78B573F12EB0A51 (score_id), PRIMARY KEY(score_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE score_game ADD CONSTRAINT FK_933B0FA12EB0A51 FOREIGN KEY (score_id) REFERENCES score (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE score_game ADD CONSTRAINT FK_933B0FAE48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE score_user ADD CONSTRAINT FK_A78B573F12EB0A51 FOREIGN KEY (score_id) REFERENCES score (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE score_user ADD CONSTRAINT FK_A78B573FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE score DROP FOREIGN KEY FK_32993751A76ED395');
        $this->addSql('ALTER TABLE score DROP FOREIGN KEY FK_32993751E48FD905');
        $this->addSql('DROP INDEX IDX_32993751A76ED395 ON score');
        $this->addSql('DROP INDEX IDX_32993751E48FD905 ON score');
        $this->addSql('ALTER TABLE score DROP user_id, DROP game_id, CHANGE score score NUMERIC(4, 0) NOT NULL');
    }
}
