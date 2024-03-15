<?php

namespace Database\DataAccess\Interfaces;

use Models\Post;

interface PostDAO
{
  public function create(Post $post): bool;
  public function getByPostId(int $id): ?array;
  public function delete(int $id): bool;
}
