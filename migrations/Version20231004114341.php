<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231004114341 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product ADD product_attribute_id INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD3B420C91 FOREIGN KEY (product_attribute_id) REFERENCES product_attribute (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04AD3B420C91 ON product (product_attribute_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD3B420C91');
        $this->addSql('DROP INDEX UNIQ_D34A04AD3B420C91 ON product');
        $this->addSql('ALTER TABLE product DROP product_attribute_id');
    }
}
