<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210815230756 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE client (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', username VARCHAR(128) NOT NULL COMMENT \'Email as the username\', password VARCHAR(96) NOT NULL COMMENT \'Use password hash with BCRYPT\', created DATETIME NOT NULL, first_name VARCHAR(96) NOT NULL, last_name VARCHAR(96) NOT NULL, UNIQUE INDEX UNIQ_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE member (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, created DATETIME NOT NULL, UNIQUE INDEX UNIQ_70E4FA78F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', vico_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', member_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', created DATETIME NOT NULL, title VARCHAR(255) NOT NULL, INDEX member_idx (member_id), INDEX vico_idx (vico_id), INDEX created_idx (created), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', title VARCHAR(50) NOT NULL, description VARCHAR(100) NOT NULL, created DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rating (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', project_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', score INT NOT NULL, comment VARCHAR(1000) NOT NULL, created DATETIME NOT NULL, INDEX IDX_D8892622166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rating_question (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', rating_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', question_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', score INT NOT NULL, INDEX IDX_E8CAFCB9A32EFC6 (rating_id), INDEX IDX_E8CAFCB91E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vico (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', name VARCHAR(64) NOT NULL, created DATETIME NOT NULL, INDEX name_idx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE19F89217 FOREIGN KEY (vico_id) REFERENCES vico (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE7597D3FE FOREIGN KEY (member_id) REFERENCES member (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D8892622166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE rating_question ADD CONSTRAINT FK_E8CAFCB9A32EFC6 FOREIGN KEY (rating_id) REFERENCES rating (id)');
        $this->addSql('ALTER TABLE rating_question ADD CONSTRAINT FK_E8CAFCB91E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE7597D3FE');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D8892622166D1F9C');
        $this->addSql('ALTER TABLE rating_question DROP FOREIGN KEY FK_E8CAFCB91E27F6BF');
        $this->addSql('ALTER TABLE rating_question DROP FOREIGN KEY FK_E8CAFCB9A32EFC6');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE19F89217');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE member');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE rating');
        $this->addSql('DROP TABLE rating_question');
        $this->addSql('DROP TABLE vico');
    }
}
