<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $response = null;

    #[ORM\ManyToOne(inversedBy: 'message_context')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Questions $question_id = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Patient $patient_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getResponse(): ?string
    {
        return $this->response;
    }

    public function setResponse(string $response): static
    {
        $this->response = $response;

        return $this;
    }

    public function getQuestionId(): ?Questions
    {
        return $this->question_id;
    }

    public function setQuestionId(?Questions $question_id): static
    {
        $this->question_id = $question_id;

        return $this;
    }

    public function getPatientId(): ?Patient
    {
        return $this->patient_id;
    }

    public function setPatientId(?Patient $patient_id): static
    {
        $this->patient_id = $patient_id;

        return $this;
    }
}
