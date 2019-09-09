<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190909103423 extends AbstractMigration
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
        $this->addSql('ALTER TABLE note ADD user_id_id INT NOT NULL, ADD creation_date DATETIME NOT NULL, CHANGE note note DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA149D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_CFBDFA149D86650F ON note (user_id_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE category CHANGE slug slug VARCHAR(50) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE diet CHANGE slug slug VARCHAR(50) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA149D86650F');
        $this->addSql('DROP INDEX IDX_CFBDFA149D86650F ON note');
        $this->addSql('ALTER TABLE note DROP user_id_id, DROP creation_date, CHANGE note note DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE recette CHANGE slug slug VARCHAR(80) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
    }
}
