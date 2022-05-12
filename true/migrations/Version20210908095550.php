<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210908095550 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE order_log (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, order_number_id INT NOT NULL, quantity INT NOT NULL, buying_price DOUBLE PRECISION NOT NULL, INDEX IDX_CC6427A54584665A (product_id), INDEX IDX_CC6427A58C26A5E8 (order_number_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_log ADD CONSTRAINT FK_CC6427A54584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE order_log ADD CONSTRAINT FK_CC6427A58C26A5E8 FOREIGN KEY (order_number_id) REFERENCES `order` (id)');
        $this->addSql('DROP TABLE order_content');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE order_content (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, ordercontent_id INT NOT NULL, product_quantity INT NOT NULL, buying_price DOUBLE PRECISION NOT NULL, INDEX IDX_8BF99E24584665A (product_id), INDEX IDX_8BF99E275BB7908 (ordercontent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE order_content ADD CONSTRAINT FK_8BF99E24584665A FOREIGN KEY (product_id) REFERENCES product (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE order_content ADD CONSTRAINT FK_8BF99E275BB7908 FOREIGN KEY (ordercontent_id) REFERENCES `order` (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE order_log');
    }
}
