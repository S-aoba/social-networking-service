<?php

namespace Database\DataAccess\Interfaces;

use Models\Post;

interface PostDAO
{
  public function create(Post $post): bool;
  public function getById(int $postId): ?array;
  public function getByUserId(int $userId): ?array;
  public function getFollowingPosts(int $userId): ?array;
  public function deletePost(int $postId): bool;
  // TODO: get post data by parentPostId
}