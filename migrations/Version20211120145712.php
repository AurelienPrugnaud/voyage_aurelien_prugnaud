<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211120145712 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE travel ADD category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE travel ADD CONSTRAINT FK_2D0B6BCE12469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)');
        $this->addSql('CREATE INDEX IDX_2D0B6BCE12469DE2 ON travel (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE travel DROP FOREIGN KEY FK_2D0B6BCE12469DE2');
        $this->addSql('DROP INDEX IDX_2D0B6BCE12469DE2 ON travel');
        $this->addSql('ALTER TABLE travel DROP category_id');
    }
}
