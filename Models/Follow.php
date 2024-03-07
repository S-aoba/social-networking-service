<?php

namespace Models;

use Models\Interfaces\Model;
use Models\Traits\GenericModel;

class Follow extends Model
{
  use GenericModel;

  protected $table = 'follows';

  public function __construct(
    private int $follow_id,
    private int $followed_id,
    private ?int $id = null,
    private string $created_at = null
  ) {
  }

  public function getFollowId(): int
  {
    return $this->follow_id;
  }

  public function setFollowId(int $follow_id): void
  {
    $this->follow_id = $follow_id;
  }

  public function getFollowedId(): int
  {
    return $this->followed_id;
  }

  public function setFollowedId(int $followed_id): void
  {
    $this->followed_id = $followed_id;
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getCreatedAt(): string
  {
    return $this->created_at;
  }

  public function setCreatedAt(string $created_at): void
  {
    $this->created_at = $created_at;
  }

  public function setId(int $id): void
  {
    $this->id = $id;
  }

  public function getTable(): string
  {
    return $this->table;
  }
}
