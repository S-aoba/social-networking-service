<?php

namespace Models;

use Models\Interfaces\Model;
use Models\Traits\GenericModel;

class Reply implements Model
{
  use GenericModel;

  public function __construct(
    private string $content,
    private int $user_id,
    private int $post_id,
    private ?int $id = null,
    private ?int $parent_reply_id = null,
    private ?string $status = null,
    private ?string $deleted_at = null,
    private ?DataTimeStamp $dataTimeStamp = null,
    private ?string $file_path = null,
    private ?string $file_type = null
  ) {
  }

  public function getContent(): string
  {
    return $this->content;
  }

  public function setContent(string $content): void
  {
    $this->content = $content;
  }

  public function getParentReplyId(): ?int
  {
    return $this->parent_reply_id;
  }

  public function getUserId(): int
  {
    return $this->user_id;
  }

  public function setUserId(int $user_id): void
  {
    $this->user_id = $user_id;
  }

  public function getPostId(): int
  {
    return $this->post_id;
  }

  public function setPostId(int $post_id): void
  {
    $this->post_id = $post_id;
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function setId(int $id): void
  {
    $this->id = $id;
  }

  public function setParentReplyId(?int $parent_reply_id): void
  {
    $this->parent_reply_id = $parent_reply_id;
  }

  public function getStatus(): ?string
  {
    return $this->status;
  }

  public function setStatus(?string $status): void
  {
    $this->status = $status;
  }

  public function getDeletedAt(): ?string
  {
    return $this->deleted_at;
  }

  public function setDeletedAt(?string $deleted_at): void
  {
    $this->deleted_at = $deleted_at;
  }

  public function getDataTimeStamp(): ?DataTimeStamp
  {
    return $this->dataTimeStamp;
  }

  public function setDataTimeStamp(?DataTimeStamp $dataTimeStamp): void
  {
    $this->dataTimeStamp = $dataTimeStamp;
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
