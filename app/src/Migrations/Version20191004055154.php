<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191004055154 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE utility_clients_product_groups (id INT NOT NULL, site_id INT NOT NULL, name VARCHAR(255) NOT NULL, guid VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_23AF7C94F6BD1646 ON utility_clients_product_groups (site_id)');
        $this->addSql('COMMENT ON COLUMN utility_clients_product_groups.id IS \'(DC2Type:utility_clients_product_group_id)\'');
        $this->addSql('COMMENT ON COLUMN utility_clients_product_groups.site_id IS \'(DC2Type:utility_clients_site_id)\'');
        $this->addSql('COMMENT ON COLUMN utility_clients_product_groups.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE utility_clients_product_groups ADD CONSTRAINT FK_23AF7C94F6BD1646 FOREIGN KEY (site_id) REFERENCES utility_clients_sites (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE utility_clients_product_groups');
    }
}
