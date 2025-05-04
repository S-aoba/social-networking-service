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
        private ?DataTimeStamp $timeStamp = null,
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

    public function getTimeStamp(): ?DataTimeStamp
    {
        return $this->timeStamp;
    }

    public function setTimeStamp(DataTimeStamp $timeStamp): void
    {
        $this->timeStamp = $timeStamp;
    }
}
