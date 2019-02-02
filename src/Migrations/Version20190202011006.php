<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190202011006 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE booking_request_group_conditions');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE booking_request_group_conditions (booking_request_id INT NOT NULL, booking_request_group_condition_id INT NOT NULL, INDEX IDX_A6C3A899DBE4DBD4 (booking_request_id), INDEX IDX_A6C3A899F6E0649E (booking_request_group_condition_id), PRIMARY KEY(booking_request_id, booking_request_group_condition_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE booking_request_group_conditions ADD CONSTRAINT FK_A6C3A899DBE4DBD4 FOREIGN KEY (booking_request_id) REFERENCES booking_request (id)');
        $this->addSql('ALTER TABLE booking_request_group_conditions ADD CONSTRAINT FK_A6C3A899F6E0649E FOREIGN KEY (booking_request_group_condition_id) REFERENCES booking_request_group_condition (id)');
    }
}
