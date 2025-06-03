<?php

namespace Models;

use Exception;
use Models\Interfaces\Model;
use Models\Traits\GenericModel;

class Notification implements Model
{
    use GenericModel;

    public const TYPES = ['follow', 'like', 'reply'];

    public function __construct(
        private int $userId,
        private string $type,
        private ?array $data,
        private ?string $reatAt = null,
        private ?DataTimeStamp $timestamp = null
    ) {
        $this->checkType();
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function setData(?array $data): void
    {
        $this->data = $data;
    }

    public function getReatAt(): ?string
    {
        return $this->reatAt;
    }

    public function setReatAt(?string $reatAt): void
    {
        $this->reatAt = $reatAt;
    }

    public function getTimestamp(): ?DataTimeStamp
    {
        return $this->timestamp;
    }

    public function setTimestamp(?DataTimeStamp $timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    private function checkType(): void
    {
        $result = in_array($this->type, self::TYPES);

        if ($result === false) {
            throw new Exception("Invalit notification type: {$this->type}");
        }

        return;
    }
}
