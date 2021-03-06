<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190416105614 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rani CHANGE nadawanie nadawanie TINYINT(1) DEFAULT \'0\', CHANGE aktywna_opaska aktywna_opaska TINYINT(1) DEFAULT \'0\', CHANGE w_akcji w_akcji TINYINT(1) DEFAULT \'1\', CHANGE data data DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rani CHANGE nadawanie nadawanie TINYINT(1) DEFAULT NULL, CHANGE aktywna_opaska aktywna_opaska TINYINT(1) DEFAULT NULL, CHANGE w_akcji w_akcji TINYINT(1) DEFAULT NULL, CHANGE data data DATE DEFAULT NULL');
    }
}
