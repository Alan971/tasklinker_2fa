<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240910205242 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projet_employe ADD CONSTRAINT FK_7A2E8EC8C18272 FOREIGN KEY (projet_id) REFERENCES projet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE projet_employe ADD CONSTRAINT FK_7A2E8EC81B65292 FOREIGN KEY (employe_id) REFERENCES employe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tache ADD CONSTRAINT FK_938720751B65292 FOREIGN KEY (employe_id) REFERENCES employe (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE tache ADD CONSTRAINT FK_93872075C18272 FOREIGN KEY (projet_id) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE tache ADD CONSTRAINT FK_93872075F6203804 FOREIGN KEY (statut_id) REFERENCES statut (id)');
        $this->addSql('ALTER TABLE user ADD auth_code VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projet_employe DROP FOREIGN KEY FK_7A2E8EC8C18272');
        $this->addSql('ALTER TABLE projet_employe DROP FOREIGN KEY FK_7A2E8EC81B65292');
        $this->addSql('ALTER TABLE user DROP auth_code');
        $this->addSql('ALTER TABLE tache DROP FOREIGN KEY FK_938720751B65292');
        $this->addSql('ALTER TABLE tache DROP FOREIGN KEY FK_93872075C18272');
        $this->addSql('ALTER TABLE tache DROP FOREIGN KEY FK_93872075F6203804');
    }
}
