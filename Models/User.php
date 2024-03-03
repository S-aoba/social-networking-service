<?php

namespace Models;

use Models\Interfaces\Model;
use Models\Traits\GenericModel;

class User implements Model
{
  use GenericModel;

  public function __construct(
    private string $username,
    private string $email,
    private ?int $id = null,
    private ?DataTimeStamp $timeStamp = null,
    private ?int $age = null,
    private ?string $address = null,
    private ?string $hobby = null,
    private ?string $self_introduction = null,
    private ?string $profile_image = null
  ) {
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function setId(int $id): void
  {
    $this->id = $id;
  }

  public function getUsername(): string
  {
    return $this->username;
  }

  public function setUsername(string $username): void
  {
    $this->username = $username;
  }

  public function getEmail(): string
  {
    return $this->email;
  }

  public function setEmail(string $email): void
  {
    $this->email = $email;
  }

  public function getTimeStamp(): ?DataTimeStamp
  {
    return $this->timeStamp;
  }

  public function setTimeStamp(DataTimeStamp $timeStamp): void
  {
    $this->timeStamp = $timeStamp;
  }

  public function getAge(): ?int
  {
    return $this->age;
  }

  public function setAge(int $age): void
  {
    $this->age = $age;
  }

  public function getAddress(): ?string
  {
    return $this->address;
  }

  public function setAddress(string $address): void
  {
    $this->address = $address;
  }

  public function getHobby(): ?string
  {
    return $this->hobby;
  }

  public function setHobby(string $hobby): void
  {
    $this->hobby = $hobby;
  }

  public function getSelfIntroduction(): ?string
  {
    return $this->self_introduction;
  }

  public function setSelfIntroduction(string $self_introduction): void
  {
    $this->self_introduction = $self_introduction;
  }

  public function getProfileImage(): ?string
  {
    return $this->profile_image;
  }

  public function setProfileImage(string $profile_image): void
  {
    $this->profile_image = $profile_image;
  }

  public function toArray(): array
  {
    return [
      'id' => $this->id,
      'username' => $this->username,
      'email' => $this->email,
      'timeStamp' => $this->timeStamp,
      'age' => $this->age,
      'address' => $this->address,
      'hobby' => $this->hobby,
      'self_introduction' => $this->self_introduction,
      'profile_image' => $this->profile_image
    ];
  }
}
