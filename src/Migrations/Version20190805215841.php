<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190805215841 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

//        $this->addSql('DROP TABLE account');
//        $this->addSql('ALTER TABLE booking_request CHANGE contact_phone contact_phone VARCHAR(200) NOT NULL');
        $this->addSql('ALTER TABLE payment_type ADD sort_order INT NOT NULL');
        $this->addSql('ALTER TABLE voucher CHANGE status status INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE account (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) NOT NULL COLLATE utf8_unicode_ci, sort_order INT NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE booking_request CHANGE contact_phone contact_phone VARCHAR(200) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE payment_type DROP sort_order');
        $this->addSql('ALTER TABLE voucher CHANGE status status INT DEFAULT 1 NOT NULL');
    }
}
