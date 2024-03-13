<?php

namespace Database\DataAccess\Interfaces;

use Models\PostLike;

interface PostLikeDAO
{
  public function addPostLike(PostLike $postLike): bool;
  public function removePostLike(PostLike $postLike): bool;
}
