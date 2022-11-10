<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190919171138 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'mysql\'.');

//        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, big_foot_sighting_id INT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_9474526C7E3C61F9 (owner_id), INDEX IDX_9474526C183C610D (big_foot_sighting_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE SEQUENCE IF NOT EXISTS comment_seq;');
        $this->addSql("CREATE TABLE IF NOT EXISTS comment (id INT DEFAULT NEXTVAL ('comment_seq') NOT NULL, owner_id INT NOT NULL, big_foot_sighting_id INT NOT NULL, content TEXT NOT NULL, created_at TIMESTAMP(0) NOT NULL, PRIMARY KEY(id))");
        $this->addSql('CREATE INDEX IF NOT EXISTS IDX_9474526C7E3C61F9 ON comment (owner_id);');
        $this->addSql('CREATE INDEX IF NOT EXISTS IDX_9474526C183C610D ON comment (big_foot_sighting_id);');

        $this->addSql('ALTER TABLE comment ADD CONSTRAINT IF NOT EXISTS FK_9474526C7E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT IF NOT EXISTS FK_9474526C183C610D FOREIGN KEY (big_foot_sighting_id) REFERENCES big_foot_sighting (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE comment');
    }
}
