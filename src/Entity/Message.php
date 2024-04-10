<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ORM\HasLifecycleCallbacks]
/**
 * TODO: Review Message class
 *
 * - Annotation for mapping Doctrine ORM is OK
 * - Properties is OK but for message status we can use Enum or final class with name MessageStatusType, because we
 * have two status sent and read, also text property can be long, greater then 255 characters
 * - Getters and Setters for encapsulation is OK also I prefer using also PHPDoc (method docblock) above method
 * IMPROVEMENTS!!!
 * - We can add missed property updated_at
 * - Also we can use HasLifecycleCallbacks to update timestamps for created_at and updated_ad
 * - I prefer add soft delete functionality
 */
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::GUID)]
    private ?string $uuid = null;
    // Achieve to text property greater than 255 characters and to be long
    // #[ORM\Column(type: Types::TEXT)]
    #[ORM\Column(length: 255)]
    private ?string $text = null;
    // Achieve to use enum for message status
    // #[ORM\Column(type: MessageStatusType::class, nullable: true)]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = null;

    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;

    #[ORM\Column(type: 'datetime')]
    private DateTime $updatedAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $deletedAt = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     * @return $this
     */
    public function setUuid(string $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return $this
     */
    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     * @return $this
     */
    public function setCreatedAt(DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     * @return $this
     */
    public function setUpdatedAt(DateTime $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getDeletedAt(): ?DateTime
    {
        return $this->deletedAt;
    }

    /**
     * @param DateTime|null $deletedAt
     * @return $this
     */
    public function setDeletedAt(?DateTime $deletedAt): static
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    // Method to handle soft deletion

    /**
     * @return void
     */
    public function softDelete(): void
    {
        $this->deletedAt = new DateTime();
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    /**
     * @return void
     */
    public function updatedTimestamps(): void
    {
        $this->setUpdatedAt(new DateTime('now'));
        if ($this->getCreatedAt() == null) {
            $this->setCreatedAt(new DateTime('now'));
        }
    }
}
