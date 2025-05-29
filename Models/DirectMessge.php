<?php

namespace Models;

use Models\Interfaces\Model;
use Models\Traits\GenericModel;

class DirectMessge implements Model
{
    use GenericModel;

    public function __construct(
        private int $conversationId,
        private int $senderId,
        private string $content,
        private ?int $id = null,
        private ?string $read_at = null,
        private ?string $createdAt = null,
    ) {
    }

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
        return $this->formatCreatedAt();
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    private function formatCreatedAt(): string
    {
        $timezone = new \DateTimeZone('Asia/Tokyo');

        $createdAt = new \DateTimeImmutable($this->createdAt, $timezone);
        $now = new \DateTimeImmutable('now', $timezone);

        $diff = $now->diff($createdAt);

        if ($diff->y > 0) {
            return $diff->y . '年前';
        } elseif ($diff->m > 0) {
            return $diff->m . 'ヶ月前';
        } elseif ($diff->d > 0) {
            return $diff->d . '日前';
        } elseif ($diff->h > 0) {
            return $diff->h . '時間前';
        } elseif ($diff->i > 0) {
            return $diff->i . '分前';
        } elseif ($diff->s > 0) {
            return $diff->s . '秒前';
        } else {
            return 'たった今';
        }
    }
}
