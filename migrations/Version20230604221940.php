<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230604221940 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create a table to collect data from Ministerstwo Edukacji i Nauki';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE mein_events (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', full_name VARCHAR(255) DEFAULT NULL, handle VARCHAR(255) DEFAULT NULL, point INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE mein_events');
    }
}
