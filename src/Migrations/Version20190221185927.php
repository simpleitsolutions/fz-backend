<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190221185927 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE product_category (id INT AUTO_INCREMENT NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, deleted DATETIME DEFAULT NULL, description VARCHAR(15) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(50) NOT NULL, email VARCHAR(254) NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9F85E0677 (username), UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE booking_request CHANGE name contact_name VARCHAR(100) NOT NULL, CHANGE phone contact_phone VARCHAR(16) NOT NULL, CHANGE email contact_email VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE product ADD product_category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADBE6903FD FOREIGN KEY (product_category_id) REFERENCES product_category (id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADBE6903FD ON product (product_category_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADBE6903FD');
        $this->addSql('DROP TABLE product_category');
        $this->addSql('DROP TABLE users');
        $this->addSql('ALTER TABLE booking_request CHANGE contact_name name VARCHAR(100) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE contact_phone phone VARCHAR(16) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE contact_email email VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('DROP INDEX IDX_D34A04ADBE6903FD ON product');
        $this->addSql('ALTER TABLE product DROP product_category_id');
    }
}
