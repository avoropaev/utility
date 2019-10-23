<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191004113531 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE utility_clients_product_groups_charges (id INT NOT NULL, product_group_id INT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, amount DOUBLE PRECISION NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A08E47DC35E4B3D0 ON utility_clients_product_groups_charges (product_group_id)');
        $this->addSql('COMMENT ON COLUMN utility_clients_product_groups_charges.id IS \'(DC2Type:utility_clients_product_groups_charge_id)\'');
        $this->addSql('COMMENT ON COLUMN utility_clients_product_groups_charges.product_group_id IS \'(DC2Type:utility_clients_product_group_id)\'');
        $this->addSql('COMMENT ON COLUMN utility_clients_product_groups_charges.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE utility_clients_product_groups_charges ADD CONSTRAINT FK_A08E47DC35E4B3D0 FOREIGN KEY (product_group_id) REFERENCES utility_clients_product_groups (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE utility_clients_product_groups_charges');
    }
}
