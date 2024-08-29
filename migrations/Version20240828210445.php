<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240828210445 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        //$this->addSql('ALTER TABLE employe DROP user_id_id');
        //$this->addSql('CREATE UNIQUE INDEX UNIQ_F804D3B9E7927C74 ON employe (email)');
        //$this->addSql('DROP INDEX UNIQ_IDENTIFIER_ID ON user');
        //$this->addSql('ALTER TABLE user ADD employe_id INT NOT NULL, ADD email VARCHAR(180) NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6491B65292 FOREIGN KEY (employe_id) REFERENCES employe (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6491B65292 ON user (employe_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_F804D3B9E7927C74 ON employe');
        $this->addSql('ALTER TABLE employe ADD user_id_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6491B65292');
        $this->addSql('DROP INDEX UNIQ_8D93D6491B65292 ON user');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_EMAIL ON user');
        $this->addSql('ALTER TABLE user DROP employe_id, DROP email');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_ID ON user (id)');
    }
}
