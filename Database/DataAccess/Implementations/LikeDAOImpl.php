<?php

namespace Database\DataAccess\Implementations;


use Database\DataAccess\Interfaces\LikeDAO;
use Models\Like;

class LikeDAOImpl implements LikeDAO {
  public function createLike(Like $like): bool
  {
    return true;
  }

  public function deleteLike(int $id): bool
  {
    return true;
  }

  public function getLikesByUserId(int $userId): array
  {
    return [];
  }

  public function getLikesByPostId(int $postId): array
  {
    return [];
  }

  public function getAllLikes(): array
  {
    return [];
  }
}