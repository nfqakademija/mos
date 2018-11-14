<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181114184915 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, region_id INT DEFAULT NULL, learning_group_id INT DEFAULT NULL, username VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', password VARCHAR(255) NOT NULL, name VARCHAR(190) DEFAULT NULL, surname VARCHAR(190) DEFAULT NULL, birth_date DATETIME DEFAULT NULL, address VARCHAR(190) DEFAULT NULL, phone VARCHAR(20) DEFAULT NULL, email VARCHAR(100) DEFAULT NULL, registration_date DATETIME NOT NULL, last_access_date DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), INDEX IDX_8D93D64998260155 (region_id), INDEX IDX_8D93D649DA24DD59 (learning_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE time_slot (id INT AUTO_INCREMENT NOT NULL, start_time DATETIME NOT NULL, duration_minutes INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(190) NOT NULL, is_city TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE learning_group (id INT AUTO_INCREMENT NOT NULL, time_slots_id INT DEFAULT NULL, address VARCHAR(190) NOT NULL, UNIQUE INDEX UNIQ_93520FFFBA14C497 (time_slots_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64998260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649DA24DD59 FOREIGN KEY (learning_group_id) REFERENCES learning_group (id)');
        $this->addSql('ALTER TABLE learning_group ADD CONSTRAINT FK_93520FFFBA14C497 FOREIGN KEY (time_slots_id) REFERENCES time_slot (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE learning_group DROP FOREIGN KEY FK_93520FFFBA14C497');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64998260155');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649DA24DD59');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE time_slot');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE learning_group');
    }
}
