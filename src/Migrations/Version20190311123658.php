<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190311123658 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

//        $this->addSql('ALTER TABLE booking_request CHANGE contact_name contact_name VARCHAR(100) NOT NULL, CHANGE contact_email contact_email VARCHAR(255) NOT NULL, CHANGE contact_phone contact_phone VARCHAR(16) NOT NULL, CHANGE comments comments VARCHAR(255) DEFAULT NULL');
//        $this->addSql('ALTER TABLE booking_request_group_condition DROP sort_order, CHANGE name name VARCHAR(50) NOT NULL');
//        $this->addSql('ALTER TABLE passenger CHANGE created created DATETIME NOT NULL, CHANGE updated updated DATETIME NOT NULL');
//        $this->addSql('ALTER TABLE users DROP expires_at, DROP confirmation_token, DROP password_requested_at, DROP credentials_expire_at, CHANGE username username VARCHAR(50) NOT NULL, CHANGE username_canonical username_canonical VARCHAR(50) NOT NULL, CHANGE email email VARCHAR(254) NOT NULL, CHANGE email_canonical email_canonical VARCHAR(254) NOT NULL, CHANGE last_login last_login DATETIME NOT NULL');
//        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9F85E0677 ON users (username)');
//        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
//        $this->addSql('ALTER TABLE voucher ADD status INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
//
//        $this->addSql('ALTER TABLE booking_request CHANGE contact_name contact_name VARCHAR(80) NOT NULL COLLATE utf8_unicode_ci, CHANGE contact_phone contact_phone VARCHAR(200) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE contact_email contact_email VARCHAR(200) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE comments comments VARCHAR(300) DEFAULT NULL COLLATE utf8_unicode_ci');
//        $this->addSql('ALTER TABLE booking_request_group_condition ADD sort_order INT NOT NULL, CHANGE name name VARCHAR(30) NOT NULL COLLATE utf8_unicode_ci');
//        $this->addSql('ALTER TABLE passenger CHANGE created created DATETIME DEFAULT NULL, CHANGE updated updated DATETIME DEFAULT NULL');
//        $this->addSql('DROP INDEX UNIQ_1483A5E9F85E0677 ON users');
//        $this->addSql('DROP INDEX UNIQ_1483A5E9E7927C74 ON users');
//        $this->addSql('ALTER TABLE users ADD expires_at DATETIME DEFAULT NULL, ADD confirmation_token VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD password_requested_at DATETIME DEFAULT NULL, ADD credentials_expire_at DATETIME DEFAULT NULL, CHANGE username username VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE username_canonical username_canonical VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE email email VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE email_canonical email_canonical VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE last_login last_login DATETIME DEFAULT NULL');
//        $this->addSql('ALTER TABLE voucher DROP status');
    }
}
