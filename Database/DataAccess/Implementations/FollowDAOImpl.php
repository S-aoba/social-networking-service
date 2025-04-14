<?php

namespace Database\DataAccess\Implementations;

use Database\DataAccess\Interfaces\FollowDAO;
use Database\DatabaseManager;
use Models\Profile;

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

  public function getFollowing(int $userId): ?array
  {
    $followingRow = $this->getRowFollowing($userId);

    if($followingRow === null) return null;

    return $followingRow;
  }

  private function getRowFollowing(int $userId): ?array {
    $mysqli = DatabaseManager::getMysqliConnection();

    $query = "SELECT profiles.*
              FROM follows 
              JOIN profiles
              ON follows.following_id = profiles.user_id
              WHERE follower_id = ?";

    $result = $mysqli->prepareAndFetchAll($query, 'i', [$userId]);

    if(!$result === null) return null;

    return $this->rowDataToProfile($result);
  }

  public function getFollower(int $userId): ?array
  {
    $followingRow = $this->getRowFollower($userId);

    if($followingRow === null) return null;

    return $followingRow;
  }

  public function checkIsFollow(int $userId, int $followingId): bool
  {
    return $this->checkRowIsFollow($userId, $followingId);
  }

  private function checkRowIsFollow(int $userId, int $followingId):bool 
  {
    $mysqli = DatabaseManager::getMysqliConnection();

    $query = "SELECT 1 FROM follows WHERE follower_id = ? AND following_id = ?";

    $result = $mysqli->prepareAndFetchAll($query, 'ii', [
      $userId,
      $followingId
    ]);

    if(count($result) === 0) return false;

    return true;
  }

  private function getRowFollower(int $userId): ?array {
    $mysqli = DatabaseManager::getMysqliConnection();

    $query = "SELECT profiles.*
              FROM follows 
              JOIN profiles
              ON follows.follower_id = profiles.user_id
              WHERE following_id = ?";

    $result = $mysqli->prepareAndFetchAll($query, 'i', [$userId]);

    if(!$result === null) return null;

    return $this->rowDataToProfile($result);
  }

  private function rowDataToProfile(array $rowData): array {
    $profiles = [];

    foreach($rowData as $row) {
      $profile = new Profile(
        username: $row['username'],
        userId: $row['user_id'],
        selfIntroduction: $row['self_introduction'],
        imagePath: $row['image_path'],
      );

      $profiles[] = $profile;
    }

    return $profiles;
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