<?php

namespace Models;

use Models\Interfaces\Model;
use Models\Traits\GenericModel;

class Profile implements Model
{
  use GenericModel;

  public function __construct(
    private int $user_id,
    private ?int $id = null,
    private ?string $username = null,
    private ?int $age = null,
    private ?string $address = null,
    private ?string $hobby = null,
    private ?string $self_introduction = null,
    private ?string $profile_image_path = null,
    private ?DataTimeStamp $timeStamp = null,
  ) {
  }

  public function getUserId(): int
  {
    return $this->user_id;
  }

  public function setUserId(int $user_id): void
  {
    $this->user_id = $user_id;
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function setId(int $id): void
  {
    $this->id = $id;
  }

  public function getUsername(): ?string
  {
    return $this->username;
  }

  public function setUsername(string $username): void
  {
    $this->username = $username;
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
    return $this->profile_image_path;
  }

  public function setProfileImage(string $profile_image_path): void
  {
    $this->profile_image_path = $profile_image_path;
  }

  public function toArray(): array
  {
    return [
      'id' => $this->id,
      'username' => $this->username,
      'age' => $this->age,
      'address' => $this->address,
      'hobby' => $this->hobby,
      'self_introduction' => $this->self_introduction,
      'profile_image_path' => $this->profile_image_path,
      'timeStamp' => $this->timeStamp,
    ];
  }
}
