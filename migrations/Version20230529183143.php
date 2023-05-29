<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20230529183143 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add column cfplinks to table cfpEvents';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE cfp_events ADD cfp_link LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE cfp_events DROP cfp_link');
    }
}
