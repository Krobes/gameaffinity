<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240511172145 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('SET FOREIGN_KEY_CHECKS=0;');
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE genre CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE platform CHANGE id id INT NOT NULL');
        $this->addSql('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE genre CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE platform CHANGE id id INT AUTO_INCREMENT NOT NULL');
    }
}
