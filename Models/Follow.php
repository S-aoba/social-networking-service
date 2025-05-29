<?php

namespace Models;

use Models\Interfaces\Model;
use Models\Traits\GenericModel;

class Follow implements Model
{
    use GenericModel;

    public function __construct(
        private ?array $followList = null,
        private ?array $followerList = null,
        private ?string $createdAt = null,
    ) {
    }

    public function getFollowList(): ?array
    {
        return $this->followList;
    }

    public function setFollowList(?array $followList): ?array
    {
        return $this->followList = $followList;
    }

    public function getFollowerList(): ?array
    {
        return $this->followerList;
    }

    public function setFollowerList(?array $followerList): ?array
    {
        return $this->followerList = $followerList;
    }

    public function getCreateAt(): ?string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?string $createdAt): ?string
    {
        return $this->$createdAt = $createdAt;
    }
}
