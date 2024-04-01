<?php

namespace Models;

use Models\Interfaces\Model;
use Models\Traits\GenericModel;

class Post implements Model
{
  use GenericModel;

  public function __construct(
    private string $content,
    private ?int $id = null,
    private ?DataTimeStamp $timeStamp = null,
    private ?int $user_id = null,
    private ?string $file_path = null,
    private ?string $file_type = null

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

  public function getContent(): string
  {
    return $this->content;
  }

  public function setContent(string $content): void
  {
    $this->content = $content;
  }

  public function getTimeStamp(): ?DataTimeStamp
  {
    return $this->timeStamp;
  }

  public function setTimeStamp(DataTimeStamp $timeStamp): void
  {
    $this->timeStamp = $timeStamp;
  }

  public function getUserId(): ?int
  {
    return $this->user_id;
  }

  public function setUserId(int $user_id): void
  {
    $this->user_id = $user_id;
  }

  public function getFilePath(): ?string
  {
    return $this->file_path;
  }

  public function setFilePath(string $file_path): void
  {
    $this->file_path = $file_path;
  }

  public function getFileType(): ?string
  {
    return $this->file_type;
  }

  public function setFileType(string $file_type): void
  {
    $this->file_type = $file_type;
  }
}
