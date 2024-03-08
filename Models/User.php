<?php

namespace Models;

use Models\Interfaces\Model;
use Models\Traits\GenericModel;

class User implements Model
{
  use GenericModel;

  public function __construct(
    private string $email,
    private ?int $id = null,
    private ?DataTimeStamp $timeStamp = null,
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


  public function getEmail(): string
  {
    return $this->email;
  }

  public function setEmail(string $email): void
  {
    $this->email = $email;
  }

  public function toArray(): array
  {
    return [
      'id' => $this->id,
      'email' => $this->email,
      'timeStamp' => $this->timeStamp,
    ];
  }
}
