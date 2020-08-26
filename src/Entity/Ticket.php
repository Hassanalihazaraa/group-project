<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TicketRepository::class)
 */
class Ticket
{
    const MEDIUM_PRIORITY = 5;
    const MIN_PRIORITY = 0;
    const MAX_PRIORITY = 10;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="ticket")
     */
    private $created_by;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $message;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creation_time;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="ticket")
     */
    private $handling_agent;

    /**
     * @ORM\OneToMany(targetEntity=CommentHistory::class, mappedBy="ticket_id", orphanRemoval=true)
     */
    private $commentHistories;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_escalated;

    /**
     * @ORM\Column(type="integer")
     */
    private $times_reopened;

    /**
     * @ORM\Column(type="integer")
     */
    private $priorities;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_message_time;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    public function __construct()
    {
        $this->commentHistories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedBy(): ?User
    {
        return $this->created_by;
    }

    public function setCreatedBy(User $user): void
    {
        $this->created_by = $user;

        //return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;

        //return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;

        //return $this;
    }

    public function getCreationTime(): ?\DateTimeInterface
    {
        return $this->creation_time;
    }

    public function setCreationTime(\DateTimeInterface $creation_time): void
    {
        $this->creation_time = $creation_time;

        //return $this;
    }

    public function getHandlingAgent(): ?User
    {
        return $this->handling_agent;
    }

    public function setHandlingAgent(?User $handling_agent): void
    {
        $this->handling_agent = $handling_agent;

        //return $this;
    }

    /**
     * @return Collection|CommentHistory[]
     */
    public function getCommentHistories(): Collection
    {
        return $this->commentHistories;
    }

    public function addCommentHistory(CommentHistory $commentHistory): self
    {
        if (!$this->commentHistories->contains($commentHistory)) {
            $this->commentHistories[] = $commentHistory;
            $commentHistory->setTicketId($this);
        }

        return $this;
    }

    public function removeCommentHistory(CommentHistory $commentHistory): self
    {
        if ($this->commentHistories->contains($commentHistory)) {
            $this->commentHistories->removeElement($commentHistory);
            // set the owning side to null (unless already changed)
            if ($commentHistory->getTicketId() === $this) {
                $commentHistory->setTicketId(null);
            }
        }

        return $this;
    }

    public function getIsEscalated(): ?bool
    {
        return $this->is_escalated;
    }

    public function setIsEscalated(bool $is_escalated): void
    {
        $this->is_escalated = $is_escalated;

        //return $this;
    }

    public function getTimesReopened(): ?int
    {
        return $this->times_reopened;
    }

    public function setTimesReopened(int $times_reopened): void
    {
        $this->times_reopened = $times_reopened;

        //return $this;
    }

    public function getPriorities(): ?int
    {
        return $this->priorities;
    }

    public function setPriorities(int $priorities): void
    {
        $this->priorities = $priorities;

        //return $this;
    }

    public function getUpdatedMessageTime(): ?\DateTimeInterface
    {
        return $this->updated_message_time;
    }

    public function setUpdatedMessageTime(?\DateTimeInterface $updated_message_time): void
    {
        $this->updated_message_time = $updated_message_time;

        //return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;

        //return $this;
    }

    public function setDefaults(): void
    {
        $this->status = 'open';
        $this->is_escalated = false;
        $this->times_reopened = 0;
        $this->priorities = self::MEDIUM_PRIORITY;
        $this->creation_time = new \DateTimeImmutable();
    }
}
