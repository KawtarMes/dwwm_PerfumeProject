<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240318151745 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favorites_perfume (favorites_id INT NOT NULL, perfume_id INT NOT NULL, INDEX IDX_B109287184DDC6B4 (favorites_id), INDEX IDX_B1092871AA91F2AA (perfume_id), PRIMARY KEY(favorites_id, perfume_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favorites_notes (favorites_id INT NOT NULL, notes_id INT NOT NULL, INDEX IDX_BF8B750284DDC6B4 (favorites_id), INDEX IDX_BF8B7502FC56F556 (notes_id), PRIMARY KEY(favorites_id, notes_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notes_olfactive_family (notes_id INT NOT NULL, olfactive_family_id INT NOT NULL, INDEX IDX_84F0C619FC56F556 (notes_id), INDEX IDX_84F0C619E48BCACA (olfactive_family_id), PRIMARY KEY(notes_id, olfactive_family_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE perfume_notes (perfume_id INT NOT NULL, notes_id INT NOT NULL, INDEX IDX_6D73051AAA91F2AA (perfume_id), INDEX IDX_6D73051AFC56F556 (notes_id), PRIMARY KEY(perfume_id, notes_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favorites_perfume ADD CONSTRAINT FK_B109287184DDC6B4 FOREIGN KEY (favorites_id) REFERENCES favorites (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorites_perfume ADD CONSTRAINT FK_B1092871AA91F2AA FOREIGN KEY (perfume_id) REFERENCES perfume (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorites_notes ADD CONSTRAINT FK_BF8B750284DDC6B4 FOREIGN KEY (favorites_id) REFERENCES favorites (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorites_notes ADD CONSTRAINT FK_BF8B7502FC56F556 FOREIGN KEY (notes_id) REFERENCES notes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notes_olfactive_family ADD CONSTRAINT FK_84F0C619FC56F556 FOREIGN KEY (notes_id) REFERENCES notes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notes_olfactive_family ADD CONSTRAINT FK_84F0C619E48BCACA FOREIGN KEY (olfactive_family_id) REFERENCES olfactive_family (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE perfume_notes ADD CONSTRAINT FK_6D73051AAA91F2AA FOREIGN KEY (perfume_id) REFERENCES perfume (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE perfume_notes ADD CONSTRAINT FK_6D73051AFC56F556 FOREIGN KEY (notes_id) REFERENCES notes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorites ADD user_id INT DEFAULT NULL, ADD olfactive_family_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE favorites ADD CONSTRAINT FK_E46960F5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE favorites ADD CONSTRAINT FK_E46960F5E48BCACA FOREIGN KEY (olfactive_family_id) REFERENCES olfactive_family (id)');
        $this->addSql('CREATE INDEX IDX_E46960F5A76ED395 ON favorites (user_id)');
        $this->addSql('CREATE INDEX IDX_E46960F5E48BCACA ON favorites (olfactive_family_id)');
        $this->addSql('ALTER TABLE media ADD perfume_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10CAA91F2AA FOREIGN KEY (perfume_id) REFERENCES perfume (id)');
        $this->addSql('CREATE INDEX IDX_6A2CA10CAA91F2AA ON media (perfume_id)');
        $this->addSql('ALTER TABLE order_purchase ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE order_purchase ADD CONSTRAINT FK_80EF338AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_80EF338AA76ED395 ON order_purchase (user_id)');
        $this->addSql('ALTER TABLE perfume ADD olfactive_family_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE perfume ADD CONSTRAINT FK_BD3A04AB106B32E FOREIGN KEY (olfactive_family_id_id) REFERENCES olfactive_family (id)');
        $this->addSql('CREATE INDEX IDX_BD3A04AB106B32E ON perfume (olfactive_family_id_id)');
        $this->addSql('ALTER TABLE purchase ADD perfume_id INT DEFAULT NULL, ADD order_purchase_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE purchase ADD CONSTRAINT FK_6117D13BAA91F2AA FOREIGN KEY (perfume_id) REFERENCES perfume (id)');
        $this->addSql('ALTER TABLE purchase ADD CONSTRAINT FK_6117D13B4E3DC13F FOREIGN KEY (order_purchase_id) REFERENCES order_purchase (id)');
        $this->addSql('CREATE INDEX IDX_6117D13BAA91F2AA ON purchase (perfume_id)');
        $this->addSql('CREATE INDEX IDX_6117D13B4E3DC13F ON purchase (order_purchase_id)');
        $this->addSql('ALTER TABLE user ADD rating_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649A32EFC6 FOREIGN KEY (rating_id) REFERENCES rating (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649A32EFC6 ON user (rating_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favorites_perfume DROP FOREIGN KEY FK_B109287184DDC6B4');
        $this->addSql('ALTER TABLE favorites_perfume DROP FOREIGN KEY FK_B1092871AA91F2AA');
        $this->addSql('ALTER TABLE favorites_notes DROP FOREIGN KEY FK_BF8B750284DDC6B4');
        $this->addSql('ALTER TABLE favorites_notes DROP FOREIGN KEY FK_BF8B7502FC56F556');
        $this->addSql('ALTER TABLE notes_olfactive_family DROP FOREIGN KEY FK_84F0C619FC56F556');
        $this->addSql('ALTER TABLE notes_olfactive_family DROP FOREIGN KEY FK_84F0C619E48BCACA');
        $this->addSql('ALTER TABLE perfume_notes DROP FOREIGN KEY FK_6D73051AAA91F2AA');
        $this->addSql('ALTER TABLE perfume_notes DROP FOREIGN KEY FK_6D73051AFC56F556');
        $this->addSql('DROP TABLE favorites_perfume');
        $this->addSql('DROP TABLE favorites_notes');
        $this->addSql('DROP TABLE notes_olfactive_family');
        $this->addSql('DROP TABLE perfume_notes');
        $this->addSql('ALTER TABLE favorites DROP FOREIGN KEY FK_E46960F5A76ED395');
        $this->addSql('ALTER TABLE favorites DROP FOREIGN KEY FK_E46960F5E48BCACA');
        $this->addSql('DROP INDEX IDX_E46960F5A76ED395 ON favorites');
        $this->addSql('DROP INDEX IDX_E46960F5E48BCACA ON favorites');
        $this->addSql('ALTER TABLE favorites DROP user_id, DROP olfactive_family_id');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10CAA91F2AA');
        $this->addSql('DROP INDEX IDX_6A2CA10CAA91F2AA ON media');
        $this->addSql('ALTER TABLE media DROP perfume_id');
        $this->addSql('ALTER TABLE order_purchase DROP FOREIGN KEY FK_80EF338AA76ED395');
        $this->addSql('DROP INDEX IDX_80EF338AA76ED395 ON order_purchase');
        $this->addSql('ALTER TABLE order_purchase DROP user_id');
        $this->addSql('ALTER TABLE perfume DROP FOREIGN KEY FK_BD3A04AB106B32E');
        $this->addSql('DROP INDEX IDX_BD3A04AB106B32E ON perfume');
        $this->addSql('ALTER TABLE perfume DROP olfactive_family_id_id');
        $this->addSql('ALTER TABLE purchase DROP FOREIGN KEY FK_6117D13BAA91F2AA');
        $this->addSql('ALTER TABLE purchase DROP FOREIGN KEY FK_6117D13B4E3DC13F');
        $this->addSql('DROP INDEX IDX_6117D13BAA91F2AA ON purchase');
        $this->addSql('DROP INDEX IDX_6117D13B4E3DC13F ON purchase');
        $this->addSql('ALTER TABLE purchase DROP perfume_id, DROP order_purchase_id');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649A32EFC6');
        $this->addSql('DROP INDEX IDX_8D93D649A32EFC6 ON user');
        $this->addSql('ALTER TABLE user DROP rating_id');
    }
}
