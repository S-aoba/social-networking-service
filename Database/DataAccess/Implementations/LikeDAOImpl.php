<?php

namespace Database\DataAccess\Implementations;


use Database\DatabaseManager;
use Database\DataAccess\Interfaces\LikeDAO;

use Models\Like;

class LikeDAOImpl implements LikeDAO 
{
    public function like(Like $like): bool
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

    public function hasLiked(Like $like): bool
    {
      return $this->checkIsLiked($like);
    }

    private function checkIsLiked(Like $like): bool 
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

    public function getLikeCount(Like $like): int
    {
      return $this->fetchLikeCount($like);
    }

    private function fetchLikeCount(Like $like): int
    {
      $mysqli = DatabaseManager::getMysqliConnection();

      $query = "SELECT COUNT(*) AS like_count
                FROM likes
                WHERE post_id = ?
                LIMIT 1
                ";
      
      $result = $mysqli->prepareAndFetchAll($query, 'i', [$like->getPostId()]);

      return $result[0]['like_count'];
    }
}