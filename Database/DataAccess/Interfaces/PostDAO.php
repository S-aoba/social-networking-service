<?php

namespace Database\DataAccess\Interfaces;

use Models\Post;

interface PostDAO
{
  public function create(Post $post): bool;
  public function getByUserId(int $userId): ?Post;
  public function getFollowingPosts(int $userId): ?array;
  // TODO: get post data by parentPostId
}