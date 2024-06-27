<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240627230817 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE message_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE patient_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE questions_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE status_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE message (id INT NOT NULL, question_id_id INT NOT NULL, patient_id_id INT NOT NULL, response TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B6BD307F4FAF8F53 ON message (question_id_id)');
        $this->addSql('CREATE INDEX IDX_B6BD307FEA724598 ON message (patient_id_id)');
        $this->addSql('CREATE TABLE patient (id INT NOT NULL, gender VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, birth_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, note TEXT NOT NULL, phone_number INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE questions (id INT NOT NULL, question VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE status (id INT NOT NULL, patient_id_id INT NOT NULL, bubble_status VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, send_sms BOOLEAN NOT NULL, is_active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7B00651CEA724598 ON status (patient_id_id)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F4FAF8F53 FOREIGN KEY (question_id_id) REFERENCES questions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FEA724598 FOREIGN KEY (patient_id_id) REFERENCES patient (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE status ADD CONSTRAINT FK_7B00651CEA724598 FOREIGN KEY (patient_id_id) REFERENCES patient (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE message_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE patient_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE questions_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE status_id_seq CASCADE');
        $this->addSql('ALTER TABLE message DROP CONSTRAINT FK_B6BD307F4FAF8F53');
        $this->addSql('ALTER TABLE message DROP CONSTRAINT FK_B6BD307FEA724598');
        $this->addSql('ALTER TABLE status DROP CONSTRAINT FK_7B00651CEA724598');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE patient');
        $this->addSql('DROP TABLE questions');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
