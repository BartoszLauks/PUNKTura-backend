<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230528235114 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create a table to collect data from WikiCFP';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE cfp_events (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', full_name VARCHAR(255) DEFAULT NULL, handle VARCHAR(255) DEFAULT NULL, year VARCHAR(255) DEFAULT NULL, location VARCHAR(255) DEFAULT NULL, begin_date VARCHAR(255) DEFAULT NULL, finish_date VARCHAR(255) DEFAULT NULL, submit_date VARCHAR(255) DEFAULT NULL, notify_date VARCHAR(255) DEFAULT NULL, weblink LONGTEXT DEFAULT NULL, info LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE cfp_events');
    }
}
