<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200428235624 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE buy (id INT AUTO_INCREMENT NOT NULL, provider_id INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, but_at DATETIME DEFAULT NULL, buy_way INT DEFAULT NULL, payment_method INT NOT NULL, total DOUBLE PRECISION NOT NULL, arrived_at DATETIME DEFAULT NULL, estimated_arrival_at DATETIME DEFAULT NULL, follow_code VARCHAR(255) DEFAULT NULL, send_way VARCHAR(255) DEFAULT NULL, send_cost DOUBLE PRECISION DEFAULT NULL, INDEX IDX_CF838277A53A8AA (provider_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE provider (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, name VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, cellphone VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, comment LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE buy_product (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, buy_id INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, unit_cost DOUBLE PRECISION NOT NULL, send_unit_cost DOUBLE PRECISION DEFAULT NULL, quantity INT NOT NULL, INDEX IDX_ED7F9FFE4584665A (product_id), INDEX IDX_ED7F9FFE4AFB9379 (buy_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE buy ADD CONSTRAINT FK_CF838277A53A8AA FOREIGN KEY (provider_id) REFERENCES provider (id)');
        $this->addSql('ALTER TABLE buy_product ADD CONSTRAINT FK_ED7F9FFE4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE buy_product ADD CONSTRAINT FK_ED7F9FFE4AFB9379 FOREIGN KEY (buy_id) REFERENCES buy (id)');
        $this->addSql('ALTER TABLE product ADD pending INT DEFAULT NULL, ADD available INT DEFAULT NULL, ADD reserved INT DEFAULT NULL, ADD code VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE wine ADD pending INT DEFAULT NULL, ADD available INT DEFAULT NULL, ADD reserved INT DEFAULT NULL, ADD code VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE buy_product DROP FOREIGN KEY FK_ED7F9FFE4AFB9379');
        $this->addSql('ALTER TABLE buy DROP FOREIGN KEY FK_CF838277A53A8AA');
        $this->addSql('DROP TABLE buy');
        $this->addSql('DROP TABLE provider');
        $this->addSql('DROP TABLE buy_product');
        $this->addSql('ALTER TABLE product DROP pending, DROP available, DROP reserved, DROP code');
        $this->addSql('ALTER TABLE wine DROP pending, DROP available, DROP reserved, DROP code');
    }
}
