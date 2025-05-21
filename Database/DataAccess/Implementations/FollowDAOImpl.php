<?php

namespace Database\DataAccess\Implementations;

use Database\DatabaseManager;
use Database\DataAccess\Interfaces\FollowDAO;

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
      $followerRow = $this->fetchFollowerCount($userId);

      return $followerRow;
    }

    private function fetchFollowerCount(int $userId): int 
    {
      $mysqli = DatabaseManager::getMysqliConnection();

      $query = "SELECT COUNT(*) FROM follows where following_id = ?";

      $result = $mysqli->prepareAndFetchAll($query, 'i', [$userId]);

      return $this->converToCount($result);
    }

    public function getFollowingCount($userId): int
    {
      $followingRow = $this->fetchFollowingCount($userId);

      return $followingRow;
    }

    private function fetchFollowingCount(int $userId): int 
    {
      $mysqli = DatabaseManager::getMysqliConnection();

      $query = "SELECT COUNT(*) FROM follows where follower_id = ?";
      
      $result = $mysqli->prepareAndFetchAll($query, 'i', [$userId]);
      
      return $this->converToCount($result);
    }

    public function getFollowing(int $userId): ?array
    {
      $followingRowData = $this->getRowFollowing($userId);

      if($followingRowData === null) return null;

      return $followingRowData;
    }

    private function getRowFollowing(int $userId): ?array 
    {
      $mysqli = DatabaseManager::getMysqliConnection();

      $query = "SELECT profiles.*
                FROM follows 
                JOIN profiles
                ON follows.following_id = profiles.user_id
                WHERE follower_id = ?";

      $result = $mysqli->prepareAndFetchAll($query, 'i', [$userId]);

      if(count($result) === 0) return null;

      return $this->rowDataToProfile($result);
    }

    public function getFollower(int $userId): ?array
    {
      $follwerRowData = $this->getRowFollower($userId);

      if($follwerRowData === null) return null;

      return $follwerRowData;
    }

    private function getRowFollower(int $userId): ?array 
    {
      $mysqli = DatabaseManager::getMysqliConnection();

      $query = "SELECT profiles.*
                FROM follows 
                JOIN profiles
                ON follows.follower_id = profiles.user_id
                WHERE following_id = ?";

      $result = $mysqli->prepareAndFetchAll($query, 'i', [$userId]);

      if(count($result) === 0) return null;

      return $this->rowDataToProfile($result);
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

    public function isMutualFollow(int $userId, int $partnerId): bool
    {
      $rowData = $this->isRowMutualFollow($userId, $partnerId);

      if($rowData === false) return false;

      return true;
    }

    private function isRowMutualFollow(int $userId, int $partnerId): bool
    {
      $mysqli = DatabaseManager::getMysqliConnection();

      $query = "SELECT
                EXISTS (
                    SELECT 1
                    FROM follows AS f1
                    JOIN follows AS f2
                      ON f1.follower_id = f2.following_id
                    AND f1.following_id = f2.follower_id
                    WHERE f1.follower_id = ?
                      AND f1.following_id = ?
                ) AS is_mutual_follow
                ";
      
      $result = $mysqli->prepareAndFetchAll($query, 'ii', [$userId, $partnerId]);

      if($result[0]['is_mutual_follow'] === 0) return false;

      return true;
    }

    private function rowDataToProfile(array $rowData): array 
    {
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

    private function converToCount(array $rowData): int 
    {
      return intval($rowData[0]['COUNT(*)']);
    }
}