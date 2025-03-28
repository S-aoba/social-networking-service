<?php

namespace Database\DataAccess\Implementations;

use Database\DataAccess\Interfaces\FollowDAO;
use Database\DatabaseManager;
use Helpers\DatabaseHelper;
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

  public function unfollow(int $userId, int $followUserId): bool
  {
    $mysqli = DatabaseManager::getMysqliConnection();

    $query = "DELETE FROM follows WHERE follower_id = ? AND following_id = ?";

    $result = $mysqli->prepareAndExecute($query, 'ii', [$userId, $followUserId]);

    if(!$result) return false;
    
    return true;
  }

  public function getFollowerCount($userId): int
  {
    $followerRow = $this->getRowFollowerCount($userId);

    return $followerRow;
  }

  public function getFollowingCount($userId): int
  {
    $followingRow = $this->getRowFollowingCount($userId);

    return $followingRow;
  }

  public function getFollowingIds(int $userId): ?array
  {
    $followingRow = $this->getRowFollowingIds($userId);

    return $followingRow;
  }

  private function getRowFollowingIds(int $userId) : ?array {
    $mysqli = DatabaseManager::getMysqliConnection();

    $query = "SELECT following_id FROM follows WHERE follower_id = ?";

    $result = $mysqli->prepareAndFetchAll($query, 'i', [$userId]) ?? null;

    if($result === null) return null;

    return $result;
  }

  private function getRowFollowerCount(int $userId): int {
    $mysqli = DatabaseManager::getMysqliConnection();

    $query = "SELECT COUNT(*) FROM follows where follower_id = ?";

    $result = $mysqli->prepareAndFetchAll($query, 'i', [$userId]);

    return $this->converToCount($result);
  }

  private function getRowFollowingCount(int $userId): int {
    $mysqli = DatabaseManager::getMysqliConnection();

    $query = "SELECT COUNT(*) FROM follows where following_id = ?";
    
    $result = $mysqli->prepareAndFetchAll($query, 'i', [$userId]);
    
    return $this->converToCount($result);
  }

  private function converToCount(array $rowData): int {
    return intval($rowData[0]['COUNT(*)']);
  }
}