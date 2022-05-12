<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210906164006 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adresse (id INT AUTO_INCREMENT NOT NULL, number VARCHAR(15) NOT NULL, line1 VARCHAR(255) DEFAULT NULL, line2 VARCHAR(255) DEFAULT NULL, line3 VARCHAR(255) DEFAULT NULL, code VARCHAR(15) NOT NULL, location VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE brand (id INT AUTO_INCREMENT NOT NULL, brand_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, category_name VARCHAR(255) NOT NULL, display_order INT NOT NULL, INDEX IDX_64C19C112469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, adresse_id INT DEFAULT NULL, genre VARCHAR(255) NOT NULL, client_name VARCHAR(255) NOT NULL, client_firstname VARCHAR(255) NOT NULL, client_dob DATE NOT NULL, email_adresse VARCHAR(255) NOT NULL, client_password VARCHAR(255) NOT NULL, INDEX IDX_C74404554DE7DC5C (adresse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, adresse_id INT NOT NULL, state_id INT NOT NULL, payment_id INT NOT NULL, order_number VARCHAR(50) NOT NULL, order_date DATE NOT NULL, INDEX IDX_F529939819EB6921 (client_id), INDEX IDX_F52993984DE7DC5C (adresse_id), INDEX IDX_F52993985D83CC1 (state_id), INDEX IDX_F52993984C3A3BB (payment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_content (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, ordercontent_id INT NOT NULL, product_quantity INT NOT NULL, buying_price DOUBLE PRECISION NOT NULL, INDEX IDX_8BF99E24584665A (product_id), INDEX IDX_8BF99E275BB7908 (ordercontent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment (id INT AUTO_INCREMENT NOT NULL, payment_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, brand_id INT DEFAULT NULL, category_id INT NOT NULL, product_name VARCHAR(255) NOT NULL, product_description LONGTEXT NOT NULL, price_ht DOUBLE PRECISION NOT NULL, tva DOUBLE PRECISION NOT NULL, is_available TINYINT(1) NOT NULL, INDEX IDX_D34A04AD44F5D008 (brand_id), INDEX IDX_D34A04AD12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_image (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, product_id INT DEFAULT NULL, url_image VARCHAR(255) NOT NULL, alt_image VARCHAR(255) NOT NULL, INDEX IDX_64617F0312469DE2 (category_id), INDEX IDX_64617F034584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE state (id INT AUTO_INCREMENT NOT NULL, state_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C112469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C74404554DE7DC5C FOREIGN KEY (adresse_id) REFERENCES adresse (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F529939819EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993984DE7DC5C FOREIGN KEY (adresse_id) REFERENCES adresse (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993985D83CC1 FOREIGN KEY (state_id) REFERENCES state (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993984C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id)');
        $this->addSql('ALTER TABLE order_content ADD CONSTRAINT FK_8BF99E24584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE order_content ADD CONSTRAINT FK_8BF99E275BB7908 FOREIGN KEY (ordercontent_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD44F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE product_image ADD CONSTRAINT FK_64617F0312469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE product_image ADD CONSTRAINT FK_64617F034584665A FOREIGN KEY (product_id) REFERENCES product (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C74404554DE7DC5C');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993984DE7DC5C');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD44F5D008');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C112469DE2');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE product_image DROP FOREIGN KEY FK_64617F0312469DE2');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F529939819EB6921');
        $this->addSql('ALTER TABLE order_content DROP FOREIGN KEY FK_8BF99E275BB7908');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993984C3A3BB');
        $this->addSql('ALTER TABLE order_content DROP FOREIGN KEY FK_8BF99E24584665A');
        $this->addSql('ALTER TABLE product_image DROP FOREIGN KEY FK_64617F034584665A');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993985D83CC1');
        $this->addSql('DROP TABLE adresse');
        $this->addSql('DROP TABLE brand');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_content');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_image');
        $this->addSql('DROP TABLE state');
    }
}
