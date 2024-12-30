<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241229091119 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $hashedPassword = password_hash('mypassword123', PASSWORD_BCRYPT);
        $this->addSql("INSERT INTO user (name, subname, email, roles, password) VALUES ('Prueba', 'Api', 'pruebas@api.com', '[\"ROLE_USER\"]', '$hashedPassword')");
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM user WHERE email = 'pruebas@api.com'");
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
    }
}
