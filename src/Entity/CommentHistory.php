<?php

namespace App\Entity;

use App\Repository\CommentHistoryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentHistoryRepository::class)
 */
class CommentHistory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Tickets::class, inversedBy="commentHistories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ticket_id;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $comments;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_private;

    /**
     * @ORM\Column(type="boolean")
     */
    private $fromManager;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="commentHistories")
     */
    private $created_by;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTicketId(): ?Tickets
    {
        return $this->ticket_id;
    }

    public function setTicketId(?Tickets $ticket_id): self
    {
        $this->ticket_id = $ticket_id;

        return $this;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(string $comments): self
    {
        $this->comments = $comments;

        return $this;
    }

    public function getIsPrivate(): ?bool
    {
        return $this->is_private;
    }

    public function setIsPrivate(bool $is_private): self
    {
        $this->is_private = $is_private;

        return $this;
    }

    public function getFromManager(): ?bool
    {
        return $this->fromManager;
    }

    public function setFromManager(bool $fromManager): self
    {
        $this->fromManager = $fromManager;

        return $this;
    }

    public function getCreatedBy(): ?Users
    {
        return $this->created_by;
    }

    public function setCreatedBy(?Users $created_by): self
    {
        $this->created_by = $created_by;

        return $this;
    }
}
