<?php

namespace Models;

use Models\Interfaces\Model;
use Models\Traits\GenericModel;

class Profile implements Model
{
    use GenericModel;

    public function __construct(
        private string $username,
        private int $userId,
        private ?int $id = null,
        private ?string $imagePath = null,
        private ?string $address = null,
        private ?string $age = null,
        private ?string $hobby = null,
        private ?string $selfIntroduction = null,
    ) {
    }

    // Getters
    public function getUsername(): string
    {
        return $this->username;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function getAge(): ?string
    {
        return $this->age;
    }

    public function getHobby(): ?string
    {
        return $this->hobby;
    }

    public function getSelfIntroduction(): ?string
    {
        return $this->selfIntroduction;
    }

    // Setters
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function setImagePath(?string $imagePath): void
    {
        $this->imagePath = $imagePath;
    }

    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    public function setAge(?string $age): void
    {
        $this->age = $age;
    }

    public function setHobby(?string $hobby): void
    {
        $this->hobby = $hobby;
    }

    public function setSelfIntroduction(?string $selfIntroduction): void
    {
        $this->selfIntroduction = $selfIntroduction;
    }
}
