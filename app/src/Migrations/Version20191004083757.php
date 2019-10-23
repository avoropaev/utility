<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191004083757 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE utility_clients_sites_product_groups (site_id INT NOT NULL, product_group_id INT NOT NULL, PRIMARY KEY(site_id, product_group_id))');
        $this->addSql('CREATE INDEX IDX_33065227F6BD1646 ON utility_clients_sites_product_groups (site_id)');
        $this->addSql('CREATE INDEX IDX_3306522735E4B3D0 ON utility_clients_sites_product_groups (product_group_id)');
        $this->addSql('COMMENT ON COLUMN utility_clients_sites_product_groups.site_id IS \'(DC2Type:utility_clients_site_id)\'');
        $this->addSql('COMMENT ON COLUMN utility_clients_sites_product_groups.product_group_id IS \'(DC2Type:utility_clients_product_group_id)\'');
        $this->addSql('ALTER TABLE utility_clients_sites_product_groups ADD CONSTRAINT FK_33065227F6BD1646 FOREIGN KEY (site_id) REFERENCES utility_clients_sites (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE utility_clients_sites_product_groups ADD CONSTRAINT FK_3306522735E4B3D0 FOREIGN KEY (product_group_id) REFERENCES utility_clients_product_groups (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE utility_clients_product_groups DROP CONSTRAINT fk_23af7c94f6bd1646');
        $this->addSql('DROP INDEX idx_23af7c94f6bd1646');
        $this->addSql('ALTER TABLE utility_clients_product_groups ADD client_id INT NOT NULL');
        $this->addSql('ALTER TABLE utility_clients_product_groups DROP site_id');
        $this->addSql('COMMENT ON COLUMN utility_clients_product_groups.client_id IS \'(DC2Type:utility_clients_client_id)\'');
        $this->addSql('ALTER TABLE utility_clients_product_groups ADD CONSTRAINT FK_23AF7C9419EB6921 FOREIGN KEY (client_id) REFERENCES utility_clients_clients (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_23AF7C9419EB6921 ON utility_clients_product_groups (client_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE utility_clients_sites_product_groups');
        $this->addSql('ALTER TABLE utility_clients_product_groups DROP CONSTRAINT FK_23AF7C9419EB6921');
        $this->addSql('DROP INDEX IDX_23AF7C9419EB6921');
        $this->addSql('ALTER TABLE utility_clients_product_groups ADD site_id INT NOT NULL');
        $this->addSql('ALTER TABLE utility_clients_product_groups DROP client_id');
        $this->addSql('COMMENT ON COLUMN utility_clients_product_groups.site_id IS \'(DC2Type:utility_clients_site_id)\'');
        $this->addSql('ALTER TABLE utility_clients_product_groups ADD CONSTRAINT fk_23af7c94f6bd1646 FOREIGN KEY (site_id) REFERENCES utility_clients_sites (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_23af7c94f6bd1646 ON utility_clients_product_groups (site_id)');
    }
}
