<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20190618161956 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Create initial tables.';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE survey (id INT AUTO_INCREMENT NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE survey_image (id INT AUTO_INCREMENT NOT NULL, survey_id INT NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', fake TINYINT(1) NOT NULL, INDEX IDX_C94DF667B3FE509D (survey_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE survey_submission (id INT AUTO_INCREMENT NOT NULL, survey_id INT NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', name VARCHAR(255) NOT NULL, submitted TINYINT(1) NOT NULL, INDEX IDX_9E7F50C4B3FE509D (survey_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE survey_submission_image (id INT AUTO_INCREMENT NOT NULL, submission_id INT NOT NULL, image_id INT NOT NULL, fake TINYINT(1) NOT NULL, INDEX IDX_39BE5E9AE1FD4933 (submission_id), INDEX IDX_39BE5E9A3DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE survey_image ADD CONSTRAINT FK_C94DF667B3FE509D FOREIGN KEY (survey_id) REFERENCES survey (id)');
        $this->addSql('ALTER TABLE survey_submission ADD CONSTRAINT FK_9E7F50C4B3FE509D FOREIGN KEY (survey_id) REFERENCES survey (id)');
        $this->addSql('ALTER TABLE survey_submission_image ADD CONSTRAINT FK_39BE5E9AE1FD4933 FOREIGN KEY (submission_id) REFERENCES survey_submission (id)');
        $this->addSql('ALTER TABLE survey_submission_image ADD CONSTRAINT FK_39BE5E9A3DA5256D FOREIGN KEY (image_id) REFERENCES survey_image (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE survey_image DROP FOREIGN KEY FK_C94DF667B3FE509D');
        $this->addSql('ALTER TABLE survey_submission DROP FOREIGN KEY FK_9E7F50C4B3FE509D');
        $this->addSql('ALTER TABLE survey_submission_image DROP FOREIGN KEY FK_39BE5E9A3DA5256D');
        $this->addSql('ALTER TABLE survey_submission_image DROP FOREIGN KEY FK_39BE5E9AE1FD4933');
        $this->addSql('DROP TABLE survey');
        $this->addSql('DROP TABLE survey_image');
        $this->addSql('DROP TABLE survey_submission');
        $this->addSql('DROP TABLE survey_submission_image');
    }
}
