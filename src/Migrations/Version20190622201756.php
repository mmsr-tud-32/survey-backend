<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190622201756 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Delete survey submission long and short';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE survey_submission_image ADD stage VARCHAR(255) NOT NULL');
        $this->addSql('DROP TABLE survey_submission_long_image');
        $this->addSql('DROP TABLE survey_submission_practise_image');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE survey_submission_image DROP stage');
        $this->addSql('CREATE TABLE survey_submission_long_image (id INT AUTO_INCREMENT NOT NULL, submission_id INT NOT NULL, image_id INT NOT NULL, fake TINYINT(1) DEFAULT NULL, INDEX IDX_3C87AE1FD4933 (submission_id), INDEX IDX_3C87A3DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE survey_submission_practise_image (id INT AUTO_INCREMENT NOT NULL, image_id INT NOT NULL, submission_id INT NOT NULL, fake TINYINT(1) DEFAULT NULL, INDEX IDX_7C51FD5BE1FD4933 (submission_id), INDEX IDX_7C51FD5B3DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE survey_submission_long_image ADD CONSTRAINT FK_3C87A3DA5256D FOREIGN KEY (image_id) REFERENCES survey_image (id)');
        $this->addSql('ALTER TABLE survey_submission_long_image ADD CONSTRAINT FK_3C87AE1FD4933 FOREIGN KEY (submission_id) REFERENCES survey_submission (id)');
        $this->addSql('ALTER TABLE survey_submission_practise_image ADD CONSTRAINT FK_7C51FD5B3DA5256D FOREIGN KEY (image_id) REFERENCES survey_image (id)');
        $this->addSql('ALTER TABLE survey_submission_practise_image ADD CONSTRAINT FK_7C51FD5BE1FD4933 FOREIGN KEY (submission_id) REFERENCES survey_submission (id)');
    }
}
