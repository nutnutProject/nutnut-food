<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190909092700 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE recette CHANGE slug slug VARCHAR(80) DEFAULT NULL');
        $this->addSql('ALTER TABLE category CHANGE slug slug VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE diet CHANGE slug slug VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE note CHANGE note note DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE category CHANGE slug slug VARCHAR(50) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE diet CHANGE slug slug VARCHAR(50) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE note CHANGE note note DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE recette CHANGE slug slug VARCHAR(80) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
    }
}
