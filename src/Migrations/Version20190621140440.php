<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20190621140440 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add practise image to submission.';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE survey_submission_practise_image (id INT AUTO_INCREMENT NOT NULL, image_id INT NOT NULL, submission_id INT NOT NULL, fake TINYINT(1) DEFAULT NULL, INDEX IDX_7C51FD5B3DA5256D (image_id), INDEX IDX_7C51FD5BE1FD4933 (submission_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE survey_submission_practise_image ADD CONSTRAINT FK_7C51FD5B3DA5256D FOREIGN KEY (image_id) REFERENCES survey_image (id)');
        $this->addSql('ALTER TABLE survey_submission_practise_image ADD CONSTRAINT FK_7C51FD5BE1FD4933 FOREIGN KEY (submission_id) REFERENCES survey_submission (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE survey_submission_practise_image');
    }
}
