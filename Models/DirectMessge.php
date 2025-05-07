<?php

namespace Models;

use Models\Interfaces\Model;
use Models\Traits\GenericModel;

class DirectMessge implements Model {
    use GenericModel;

    public function __construct(
        private int $conversationId,
        private int $senderId,
        private string $content,
        private ?int $id = null,
        private ?string $read_at = null,
        private ?string $createdAt = null,
    ) {}

    public function getConversationId(): int
    {
        return $this->conversationId;
    }

    public function setConversationId(int $conversationId): void
    {
        $this->conversationId = $conversationId;
    }

    public function getSenderId(): int
    {
        return $this->senderId;
    }

    public function setSenderId(int $senderId): void
    {
        $this->senderId = $senderId;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getReadAt(): ?string
    {
        return $this->read_at;
    }

    public function setReadAt(?string $read_at): void
    {
        $this->read_at = $read_at;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
