<?php

namespace App\Entity;

use App\Repository\StatusRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatusRepository::class)]
class Status
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $bubble_status = null;

    #[ORM\Column(length: 255)]
    #[ORM\JoinColumn(nullable: true)]
    private ?string $type = null;

    #[ORM\OneToOne(inversedBy: 'status', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Patient $patient_id = null;

    #[ORM\Column]
    private ?bool $send_sms = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBubbleStatus(): ?string
    {
        return $this->bubble_status;
    }

    public function setBubbleStatus(string $bubble_status): static
    {
        $this->bubble_status = $bubble_status;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getPatientId(): ?Patient
    {
        return $this->patient_id;
    }

    public function setPatientId(Patient $patient_id): static
    {
        $this->patient_id = $patient_id;

        return $this;
    }

    public function isSendSms(): ?bool
    {
        return $this->send_sms;
    }

    public function setSendSms(bool $send_sms): static
    {
        $this->send_sms = $send_sms;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }
}
