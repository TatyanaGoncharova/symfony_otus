<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220603151425 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE "course" (id BIGSERIAL NOT NULL, title VARCHAR(32) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "lesson" (id BIGSERIAL NOT NULL, course_id BIGINT DEFAULT NULL, title VARCHAR(32) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX lesson__course_id__ind ON "lesson" (course_id)');
        $this->addSql('CREATE TABLE "progress" (id BIGSERIAL NOT NULL, user_id BIGINT DEFAULT NULL, task_id BIGINT DEFAULT NULL, rate INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX progress__user_id__ind ON "progress" (user_id)');
        $this->addSql('CREATE INDEX progress__task_id__ind ON "progress" (task_id)');
        $this->addSql('CREATE TABLE "skill" (id BIGSERIAL NOT NULL, skill_name VARCHAR(32) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "task" (id BIGSERIAL NOT NULL, lesson_id BIGINT DEFAULT NULL, title VARCHAR(32) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX task__lesson_id__ind ON "task" (lesson_id)');
        $this->addSql('CREATE TABLE "unit" (id BIGSERIAL NOT NULL, task_id BIGINT DEFAULT NULL, skill_id BIGINT DEFAULT NULL, percent INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX unit__task_id__ind ON "unit" (task_id)');
        $this->addSql('CREATE INDEX unit__skill_id__ind ON "unit" (skill_id)');
        $this->addSql('CREATE TABLE "user" (id BIGSERIAL NOT NULL, login VARCHAR(32) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE "lesson" ADD CONSTRAINT FK_lesson_course_id FOREIGN KEY (course_id) REFERENCES "course" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "progress" ADD CONSTRAINT FK_progress_user_id FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "progress" ADD CONSTRAINT FK_progress_task_id FOREIGN KEY (task_id) REFERENCES "task" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "task" ADD CONSTRAINT FK_task_lesson_id FOREIGN KEY (lesson_id) REFERENCES "lesson" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "unit" ADD CONSTRAINT FK_unit_task_id FOREIGN KEY (task_id) REFERENCES "task" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "unit" ADD CONSTRAINT FK_unit_skill_id FOREIGN KEY (skill_id) REFERENCES "skill" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "lesson" DROP CONSTRAINT FK_lesson_course_id');
        $this->addSql('ALTER TABLE "task" DROP CONSTRAINT FK_task_lesson_id');
        $this->addSql('ALTER TABLE "unit" DROP CONSTRAINT FK_skill_id');
        $this->addSql('ALTER TABLE "progress" DROP CONSTRAINT FK_progress_task_id');
        $this->addSql('ALTER TABLE "unit" DROP CONSTRAINT FK_unit_task_id');
        $this->addSql('ALTER TABLE "progress" DROP CONSTRAINT FK_unit_progress_user_id');
        $this->addSql('DROP TABLE "course"');
        $this->addSql('DROP TABLE "lesson"');
        $this->addSql('DROP TABLE "progress"');
        $this->addSql('DROP TABLE "skill"');
        $this->addSql('DROP TABLE "task"');
        $this->addSql('DROP TABLE "unit"');
        $this->addSql('DROP TABLE "user"');
    }
}
