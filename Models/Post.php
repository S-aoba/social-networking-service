<?php

namespace Models;

use Models\Interfaces\Model;
use Models\Traits\GenericModel;

class Post implements Model {
    use GenericModel;

    public function __construct(
        private string $content,
        private int $userId,
        private ?int $id = null,
        private ?string $imagePath = null,
        private ?int $parentPostId = null,
        private ?string $createdAt = null,
    ) {}

    public function getId(): ?int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getContent(): string {
        return $this->content;
    }

    public function setContent(string $content): void {
        $this->content = $content;
    }

    public function getUserId(): int {
        return $this->userId;
    }

    public function setUserId(int $userId): void {
        $this->userId = $userId;
    }

    public function getImagePath(): ?string {
        return $this->imagePath;
    }

    public function setImagePath(?string $imagePath): void {
        $this->imagePath = $imagePath;
    }

    public function getParentPostId(): ?int {
        return $this->parentPostId;
    }

    public function setParentPostId(int $parentPostId): void {
        $this->parentPostId = $parentPostId;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getFormattedCreatedAt(): string
    {
        return $this->formatCreatedAt();
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