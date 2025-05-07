<?php

namespace Models;

use Models\Interfaces\Model;
use Models\Traits\GenericModel;

class Conversation implements Model {
    use GenericModel;

    public function __construct(
        private int $user1Id,
        private int $user2Id,
        private ?int $id = null,
        private ?string $createdAt = null,
    ) {}

    public function getUser1Id(): int
    {
        return $this->user1Id;
    }

    public function setUser1Id(int $user1Id): void
    {
        $this->user1Id = $user1Id;
    }

    public function getUser2Id(): int
    {
        return $this->user2Id;
    }

    public function setUser2Id(int $user2Id): void
    {
        $this->user2Id = $user2Id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
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
