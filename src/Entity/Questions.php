<?php

namespace App\Entity;

use App\Repository\QuestionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionsRepository::class)]
class Questions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $question = null;

    /**
     * @var Collection<int, Message>
     */
    #[ORM\OneToMany(mappedBy: 'questionÂ_id', targetEntity: Message::class)]
    private Collection $message_context;

    public function __construct()
    {
        $this->message_context = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): static
    {
        $this->question = $question;

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessageContext(): Collection
    {
        return $this->message_context;
    }

    public function addMessageContext(Message $messageContext): static
    {
        if (!$this->message_context->contains($messageContext)) {
            $this->message_context->add($messageContext);
            $messageContext->setQuestionÂId($this);
        }

        return $this;
    }

    public function removeMessageContext(Message $messageContext): static
    {
        if ($this->message_context->removeElement($messageContext)) {
            // set the owning side to null (unless already changed)
            if ($messageContext->getQuestionÂId() === $this) {
                $messageContext->setQuestionÂId(null);
            }
        }

        return $this;
    }
}
