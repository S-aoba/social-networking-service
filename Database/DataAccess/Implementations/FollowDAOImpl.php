<?php

namespace Database\DataAccess\Implementations;

use Database\DataAccess\Interfaces\FollowDAO;
use Database\DatabaseManager;
use Models\Follow;

class FollowDAOImpl implements FollowDAO
{
  public function follow(int $userId, int $followUserId): bool
  {
    $mysqli = DatabaseManager::getMysqliConnection();

    $query = "INSERT INTO follows (follower_id, following_id) VALUES (?, ?)";

    $result = $mysqli->prepareAndExecute($query, 'ii', [$userId, $followUserId]);

    if(!$result) return false;
    
    return true;
  }

  public function unFollow(int $userId, int $followUserId): bool
  {
    return false;
  }

  public function getFollowList($userId): ?Follow
  {
    return null;
  }

  public function getFollowerList($userId): ?Follow
  {
    return null;
  }

  private function getRowFollowList(int $userId): ?array {
    return null;
  }

  private function getRowFollowerList(int $userId): ?array {
    return null;
  }

  private function rowDataToFollow(array $rowData): ?Follow {
    return null;
  }
}