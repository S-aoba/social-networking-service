<?php

namespace Database\DataAccess\Interfaces;

use Models\PostLike;

interface PostLikeDAO
{
  public function addPostLike(PostLike $postLike, string $content): bool;
  public function removePostLike(PostLike $postLike): bool;
  public function getLikeCountByPostId(int $post_id): array;
  public function getLikeByUserId(int $user_id, int $post_id): bool;
}
