<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260512145656 extends AbstractMigration
{
   public function getDescription(): string
   {
      return '';
   }

   public function up(Schema $schema): void
   {
      // this up() migration is auto-generated, please modify it to your needs
      $this->addSql('ALTER TABLE one_time_passwords ADD attempts INT NOT NULL');
      $this->addSql('ALTER TABLE one_time_passwords ADD delivery_method VARCHAR(255) NOT NULL');
      $this->addSql('ALTER TABLE one_time_passwords DROP ip_address');
      $this->addSql('ALTER TABLE one_time_passwords DROP user_agent');
      $this->addSql('ALTER TABLE one_time_passwords DROP used_at');
      $this->addSql('ALTER TABLE one_time_passwords RENAME COLUMN status TO type');
   }

   public function down(Schema $schema): void
   {
      // this down() migration is auto-generated, please modify it to your needs
      $this->addSql('ALTER TABLE one_time_passwords ADD ip_address VARCHAR(255) DEFAULT NULL');
      $this->addSql('ALTER TABLE one_time_passwords ADD user_agent VARCHAR(255) DEFAULT NULL');
      $this->addSql('ALTER TABLE one_time_passwords ADD status VARCHAR(255) NOT NULL');
      $this->addSql('ALTER TABLE one_time_passwords ADD used_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
      $this->addSql('ALTER TABLE one_time_passwords DROP attempts');
      $this->addSql('ALTER TABLE one_time_passwords DROP type');
      $this->addSql('ALTER TABLE one_time_passwords DROP delivery_method');
   }
}
