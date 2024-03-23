<?php

namespace Models;

use Models\Interfaces\Model;
use Models\Traits\GenericModel;

class PostLike implements Model
{
  use GenericModel;

  public function __construct(
    private int $user_id,
    private int $post_id,
    private int $post_user_id
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

  public function getPostId(): int
  {
    return $this->post_id;
  }

  public function setPostId(int $post_id): void
  {
    $this->post_id = $post_id;
  }

  public function getPostUserId(): int
  {
    return $this->post_user_id;
  }

  public function setPostUserId(int $post_user_id): void
  {
    $this->post_user_id = $post_user_id;
  }
}
