<?php

namespace Database\DataAccess\Interfaces;

use Models\Post;

interface PostDAO
{
  public function create(Post $post): bool;
  public function getById(int $id): ?Post;
  public function getByUserId(int $userId): ?Post;
  // TODO: get post data by parentPostId
}