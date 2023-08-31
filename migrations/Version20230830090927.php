<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230830090927 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE coupon_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE coupon (id INT NOT NULL, code VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, discount DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql("INSERT INTO coupon (id, code, type, discount) VALUES ((SELECT nextval('coupon_id_seq')), 'F05', 'fixed', 5)");
        $this->addSql("INSERT INTO coupon (id, code, type, discount) VALUES ((SELECT nextval('coupon_id_seq')), 'P06', 'procent', 6)");
        $this->addSql("INSERT INTO coupon (id, code, type, discount) VALUES ((SELECT nextval('coupon_id_seq')), 'F110', 'fixed', 110)");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE coupon_id_seq CASCADE');
        $this->addSql('DROP TABLE coupon');
    }
}
