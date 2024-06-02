<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240522062724 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE personal_list (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_75C2FF25A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE personal_list_game (personal_list_id INT NOT NULL, game_id INT NOT NULL, INDEX IDX_6949288FBE891FA0 (personal_list_id), INDEX IDX_6949288FE48FD905 (game_id), PRIMARY KEY(personal_list_id, game_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE personal_list ADD CONSTRAINT FK_75C2FF25A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE personal_list_game ADD CONSTRAINT FK_6949288FBE891FA0 FOREIGN KEY (personal_list_id) REFERENCES personal_list (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE personal_list_game ADD CONSTRAINT FK_6949288FE48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE personal_list DROP FOREIGN KEY FK_75C2FF25A76ED395');
        $this->addSql('ALTER TABLE personal_list_game DROP FOREIGN KEY FK_6949288FBE891FA0');
        $this->addSql('ALTER TABLE personal_list_game DROP FOREIGN KEY FK_6949288FE48FD905');
        $this->addSql('DROP TABLE personal_list');
        $this->addSql('DROP TABLE personal_list_game');
    }
}
