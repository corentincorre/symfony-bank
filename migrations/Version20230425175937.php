<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230425175937 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__transaction AS SELECT id, receiver_id, mode, sender, amount FROM "transaction"');
        $this->addSql('DROP TABLE "transaction"');
        $this->addSql('CREATE TABLE "transaction" (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, receiver_id INTEGER NOT NULL, mode INTEGER NOT NULL, sender INTEGER NOT NULL, amount DOUBLE PRECISION NOT NULL, CONSTRAINT FK_723705D1CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES account (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO "transaction" (id, receiver_id, mode, sender, amount) SELECT id, receiver_id, mode, sender, amount FROM __temp__transaction');
        $this->addSql('DROP TABLE __temp__transaction');
        $this->addSql('CREATE INDEX IDX_723705D1CD53EDB6 ON "transaction" (receiver_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__transaction AS SELECT id, receiver_id, mode, sender, amount FROM "transaction"');
        $this->addSql('DROP TABLE "transaction"');
        $this->addSql('CREATE TABLE "transaction" (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, receiver_id INTEGER NOT NULL, mode INTEGER NOT NULL, sender INTEGER NOT NULL, amount DOUBLE PRECISION NOT NULL, CONSTRAINT FK_723705D1CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO "transaction" (id, receiver_id, mode, sender, amount) SELECT id, receiver_id, mode, sender, amount FROM __temp__transaction');
        $this->addSql('DROP TABLE __temp__transaction');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_723705D1CD53EDB6 ON "transaction" (receiver_id)');
    }
}
