<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20230530200229 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add columns cfp_link, begin_date_format, submit_date_format, finish_date_format';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE cfp_events ADD cfp_link LONGTEXT DEFAULT NULL, ADD begin_date_format DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD submit_date_format DATETIME DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', ADD finish_date_format DATETIME DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE cfp_events DROP cfp_link, DROP begin_date_format, DROP submit_date_format, DROP finish_date_format');
    }
}
