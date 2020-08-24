<?php

namespace App\Entity;

use App\Repository\TicketsRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass=TicketsRepository::class)
 */
class Tickets
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="tickets")
     */
    private $created_by;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $message_customer;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $message_public_agent;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creation_time;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="tickets")
     */
    private $handling_agent;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $message_private_agent;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedBy(): ?int
    {
        return $this->created_by;
    }

    public function setCreatedBy(int $created_by): self
    {
        $this->created_by = $created_by;

        return $this;
    }

    public function getMessageCustomer(): ?string
    {
        return $this->message_customer;
    }

    public function setMessageCustomer(string $message_customer): self
    {
        $this->message_customer = $message_customer;

        return $this;
    }

    public function getMessagePublicAgent(): ?string
    {
        return $this->message_public_agent;
    }

    public function setMessagePublicAgent(?string $message_public_agent): self
    {
        $this->message_public_agent = $message_public_agent;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreationTime(): ?\DateTimeInterface
    {
        return $this->creation_time;
    }

    public function setCreationTime(\DateTimeInterface $creation_time): self
    {
        $this->creation_time = $creation_time;

        return $this;
    }

    public function getHandlingAgent(): ?Users
    {
        return $this->handling_agent;
    }

    public function setHandlingAgent(?Users $handling_agent): self
    {
        $this->handling_agent = $handling_agent;

        return $this;
    }

    public function getMessagePrivateAgent(): ?string
    {
        return $this->message_private_agent;
    }

    public function setMessagePrivateAgent(?string $message_private_agent): self
    {
        $this->message_private_agent = $message_private_agent;

        return $this;
    }
}
