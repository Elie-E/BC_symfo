<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210908184843 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C74404554DE7DC5C');
        $this->addSql('DROP INDEX IDX_C74404554DE7DC5C ON client');
        $this->addSql('ALTER TABLE client ADD email VARCHAR(180) NOT NULL, ADD roles JSON NOT NULL, ADD password VARCHAR(255) NOT NULL, DROP adresse_id, DROP email_adresse, DROP client_password, CHANGE genre genre TINYINT(1) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7440455E7927C74 ON client (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_C7440455E7927C74 ON client');
        $this->addSql('ALTER TABLE client ADD adresse_id INT DEFAULT NULL, ADD client_password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP email, DROP roles, CHANGE genre genre VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE password email_adresse VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C74404554DE7DC5C FOREIGN KEY (adresse_id) REFERENCES adresse (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_C74404554DE7DC5C ON client (adresse_id)');
    }
}
