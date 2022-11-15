<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221115085425 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classe DROP FOREIGN KEY FK_8F87BF96ABC1F7FE');
        $this->addSql('ALTER TABLE classe ADD CONSTRAINT FK_8F87BF96ABC1F7FE FOREIGN KEY (prof_id) REFERENCES prof (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE eleve DROP FOREIGN KEY FK_ECA105F7ABC1F7FE');
        $this->addSql('ALTER TABLE eleve ADD CONSTRAINT FK_ECA105F7ABC1F7FE FOREIGN KEY (prof_id) REFERENCES prof (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classe DROP FOREIGN KEY FK_8F87BF96ABC1F7FE');
        $this->addSql('ALTER TABLE classe ADD CONSTRAINT FK_8F87BF96ABC1F7FE FOREIGN KEY (prof_id) REFERENCES prof (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE eleve DROP FOREIGN KEY FK_ECA105F7ABC1F7FE');
        $this->addSql('ALTER TABLE eleve ADD CONSTRAINT FK_ECA105F7ABC1F7FE FOREIGN KEY (prof_id) REFERENCES prof (id) ON DELETE CASCADE');
    }
}
