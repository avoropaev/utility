<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191004040022 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE utility_clients_sites (id INT NOT NULL, client_id INT NOT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_577AB87219EB6921 ON utility_clients_sites (client_id)');
        $this->addSql('COMMENT ON COLUMN utility_clients_sites.id IS \'(DC2Type:utility_clients_site_id)\'');
        $this->addSql('COMMENT ON COLUMN utility_clients_sites.client_id IS \'(DC2Type:utility_clients_client_id)\'');
        $this->addSql('COMMENT ON COLUMN utility_clients_sites.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE utility_clients_sites ADD CONSTRAINT FK_577AB87219EB6921 FOREIGN KEY (client_id) REFERENCES utility_clients_clients (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP INDEX uniq_a0a897565e237e06');
        $this->addSql('ALTER TABLE utility_clients_clients ALTER secret_key TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE utility_clients_clients ALTER secret_key DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN utility_clients_clients.secret_key IS \'(DC2Type:utility_clients_client_secret_key)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE utility_clients_sites');
        $this->addSql('ALTER TABLE utility_clients_clients ALTER secret_key TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE utility_clients_clients ALTER secret_key DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN utility_clients_clients.secret_key IS NULL');
        $this->addSql('CREATE UNIQUE INDEX uniq_a0a897565e237e06 ON utility_clients_clients (name)');
    }
}
