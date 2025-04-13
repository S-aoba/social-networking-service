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
    $mysqli = DatabaseManager::getMysqliConnection();

    $query = "DELETE FROM likes WHERE user_id = ? AND post_id = ?";
    
    $result = $mysqli->prepareAndExecute($query, 'ii', [
      $like->getUserId(),
      $like->getPostId()
    ]);

    if (!$result) {
      return false;
    }

    return true;
  }
}