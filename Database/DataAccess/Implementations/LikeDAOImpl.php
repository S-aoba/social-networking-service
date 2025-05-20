<?php

namespace Database\DataAccess\Implementations;


use Database\DatabaseManager;
use Database\DataAccess\Interfaces\LikeDAO;

use Models\Like;

class LikeDAOImpl implements LikeDAO 
{
    // Public
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

    public function checkIsLiked(Like $like): bool
    {
      return $this->checkRowIsLiked($like);
    }

    // Private
    private function checkRowIsLiked(Like $like): bool 
    {
      $mysqli = DatabaseManager::getMysqliConnection();

      $query = "SELECT 1 FROM likes WHERE user_id = ? AND post_id = ?";

      $result = $mysqli->prepareAndFetchAll($query, 'ii', [
        $like->getUserId(),
        $like->getPostId()
      ]);
      
      if(count($result) === 0) return false;

      return true;
    }
}