<?php

namespace Database\DataAccess\Interfaces;

use Models\Post;

interface PostDAO
{
  public function create(Post $post): bool;
  public function delete(int $id): bool;
  public function getByPostId(int $id): ?array;

  public function getTrendingPosts(int $offset, int $limit): array;
  public function getFollowerPost(int $offset, int $limit): array;

  public function getPublicPosts(int $offset, int $limit): ?array;

  public function getAllPostByUserId(int $user_id): ?array;
}
