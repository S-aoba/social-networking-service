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
    private ?string $image_path = null,
    private ?string $video_path = null

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

  public function getImagePath(): ?string
  {
    return $this->image_path;
  }

  public function setImagePath(string $image_path): void
  {
    $this->image_path = $image_path;
  }

  public function getVideoPath(): ?string
  {
    return $this->video_path;
  }

  public function setVideoPath(string $video_path): void
  {
    $this->video_path = $video_path;
  }
}
