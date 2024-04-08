<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240326095357 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE olfactive_family_notes (olfactive_family_id INT NOT NULL, notes_id INT NOT NULL, INDEX IDX_CF598BF5E48BCACA (olfactive_family_id), INDEX IDX_CF598BF5FC56F556 (notes_id), PRIMARY KEY(olfactive_family_id, notes_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE olfactive_family_notes ADD CONSTRAINT FK_CF598BF5E48BCACA FOREIGN KEY (olfactive_family_id) REFERENCES olfactive_family (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE olfactive_family_notes ADD CONSTRAINT FK_CF598BF5FC56F556 FOREIGN KEY (notes_id) REFERENCES notes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notes_olfactive_family DROP FOREIGN KEY FK_84F0C619FC56F556');
        $this->addSql('ALTER TABLE notes_olfactive_family DROP FOREIGN KEY FK_84F0C619E48BCACA');
        $this->addSql('DROP TABLE notes_olfactive_family');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE notes_olfactive_family (notes_id INT NOT NULL, olfactive_family_id INT NOT NULL, INDEX IDX_84F0C619FC56F556 (notes_id), INDEX IDX_84F0C619E48BCACA (olfactive_family_id), PRIMARY KEY(notes_id, olfactive_family_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE notes_olfactive_family ADD CONSTRAINT FK_84F0C619FC56F556 FOREIGN KEY (notes_id) REFERENCES notes (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notes_olfactive_family ADD CONSTRAINT FK_84F0C619E48BCACA FOREIGN KEY (olfactive_family_id) REFERENCES olfactive_family (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE olfactive_family_notes DROP FOREIGN KEY FK_CF598BF5E48BCACA');
        $this->addSql('ALTER TABLE olfactive_family_notes DROP FOREIGN KEY FK_CF598BF5FC56F556');
        $this->addSql('DROP TABLE olfactive_family_notes');
    }
}
