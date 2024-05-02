<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240424121309 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE adress_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE payment_method_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE adress (id INT NOT NULL, owner_id INT NOT NULL, street_number INT NOT NULL, street TEXT NOT NULL, zipcode INT NOT NULL, city TEXT NOT NULL, phone VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5CECC7BE7E3C61F9 ON adress (owner_id)');
        $this->addSql('CREATE TABLE payment_method (id INT NOT NULL, owner_id INT NOT NULL, card_number VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7B61A1F67E3C61F9 ON payment_method (owner_id)');
        $this->addSql('ALTER TABLE adress ADD CONSTRAINT FK_5CECC7BE7E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payment_method ADD CONSTRAINT FK_7B61A1F67E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE adress_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE payment_method_id_seq CASCADE');
        $this->addSql('ALTER TABLE adress DROP CONSTRAINT FK_5CECC7BE7E3C61F9');
        $this->addSql('ALTER TABLE payment_method DROP CONSTRAINT FK_7B61A1F67E3C61F9');
        $this->addSql('DROP TABLE adress');
        $this->addSql('DROP TABLE payment_method');
    }
}
