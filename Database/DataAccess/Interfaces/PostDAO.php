<?php

namespace Database\DataAccess\Interfaces;

use Models\Post;

interface PostDAO
{
  public function create(string $content, int $userId): bool;
  public function getById(int $id): ?Post;
  public function delete(int $id): bool;
}
