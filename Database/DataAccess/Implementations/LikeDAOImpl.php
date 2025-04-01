<?php

namespace Database\DataAccess\Implementations;


use Database\DataAccess\Interfaces\LikeDAO;
use Database\DatabaseManager;
use Models\Like;

class LikeDAOImpl implements LikeDAO {
  public function createLike(Like $like): bool
  {
    $mysqli = DatabaseManager::getMysqliConnection();

    $query = "INSERT INTO likes (user_id, post_id) VALUES (?, ?)";

    $result = $mysqli->prepareAndExecute($query, 'ii', [
      $like->getUserId(),
      $like->getPostId()
    ]);

    if (!$result) {
      return false;
    }

    return true;
  }

  public function unlike(Like $like): bool
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