<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190903090525 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE recette_note (recette_id INT NOT NULL, note_id INT NOT NULL, INDEX IDX_82BD104F89312FE9 (recette_id), INDEX IDX_82BD104F26ED0855 (note_id), PRIMARY KEY(recette_id, note_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recette_diet (recette_id INT NOT NULL, diet_id INT NOT NULL, INDEX IDX_D0E48F7B89312FE9 (recette_id), INDEX IDX_D0E48F7BE1E13ACE (diet_id), PRIMARY KEY(recette_id, diet_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recette_note ADD CONSTRAINT FK_82BD104F89312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recette_note ADD CONSTRAINT FK_82BD104F26ED0855 FOREIGN KEY (note_id) REFERENCES note (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recette_diet ADD CONSTRAINT FK_D0E48F7B89312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recette_diet ADD CONSTRAINT FK_D0E48F7BE1E13ACE FOREIGN KEY (diet_id) REFERENCES diet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recette ADD category_id INT NOT NULL, ADD user_id INT NOT NULL, CHANGE slug slug VARCHAR(80) DEFAULT NULL, CHANGE photo photo VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT FK_49BB639012469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT FK_49BB6390A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_49BB639012469DE2 ON recette (category_id)');
        $this->addSql('CREATE INDEX IDX_49BB6390A76ED395 ON recette (user_id)');
        $this->addSql('ALTER TABLE category CHANGE slug slug VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE diet CHANGE slug slug VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE ingredient ADD recette_id INT NOT NULL');
        $this->addSql('ALTER TABLE ingredient ADD CONSTRAINT FK_6BAF787089312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id)');
        $this->addSql('CREATE INDEX IDX_6BAF787089312FE9 ON ingredient (recette_id)');
        $this->addSql('ALTER TABLE note CHANGE note note DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE user_request ADD recette_id INT NOT NULL, ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_request ADD CONSTRAINT FK_639A919589312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id)');
        $this->addSql('ALTER TABLE user_request ADD CONSTRAINT FK_639A9195A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_639A919589312FE9 ON user_request (recette_id)');
        $this->addSql('CREATE INDEX IDX_639A9195A76ED395 ON user_request (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE recette_note');
        $this->addSql('DROP TABLE recette_diet');
        $this->addSql('ALTER TABLE category CHANGE slug slug VARCHAR(50) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE diet CHANGE slug slug VARCHAR(50) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE ingredient DROP FOREIGN KEY FK_6BAF787089312FE9');
        $this->addSql('DROP INDEX IDX_6BAF787089312FE9 ON ingredient');
        $this->addSql('ALTER TABLE ingredient DROP recette_id');
        $this->addSql('ALTER TABLE note CHANGE note note DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE recette DROP FOREIGN KEY FK_49BB639012469DE2');
        $this->addSql('ALTER TABLE recette DROP FOREIGN KEY FK_49BB6390A76ED395');
        $this->addSql('DROP INDEX IDX_49BB639012469DE2 ON recette');
        $this->addSql('DROP INDEX IDX_49BB6390A76ED395 ON recette');
        $this->addSql('ALTER TABLE recette DROP category_id, DROP user_id, CHANGE slug slug VARCHAR(80) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE photo photo VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
        $this->addSql('ALTER TABLE user_request DROP FOREIGN KEY FK_639A919589312FE9');
        $this->addSql('ALTER TABLE user_request DROP FOREIGN KEY FK_639A9195A76ED395');
        $this->addSql('DROP INDEX IDX_639A919589312FE9 ON user_request');
        $this->addSql('DROP INDEX IDX_639A9195A76ED395 ON user_request');
        $this->addSql('ALTER TABLE user_request DROP recette_id, DROP user_id');
    }
}
