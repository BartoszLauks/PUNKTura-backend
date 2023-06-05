<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230605000114 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add columns point, clear_full_name, clear_handle';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE cfp_events ADD point INT DEFAULT NULL, ADD clear_full_name VARCHAR(255) DEFAULT NULL, ADD clear_handle VARCHAR(255) DEFAULT NULL, CHANGE submit_date_format submit_date_format DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE finish_date_format finish_date_format DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE cfp_events DROP point, DROP clear_full_name, DROP clear_handle, CHANGE submit_date_format submit_date_format DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', CHANGE finish_date_format finish_date_format DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\'');
    }
}
