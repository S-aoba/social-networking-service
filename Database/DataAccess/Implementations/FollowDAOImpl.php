<?php

namespace Database\DataAccess\Implementations;

use Database\DataAccess\Interfaces\FollowDAO;
use Database\DatabaseManager;
use Models\Follow;
use Models\Profile;
use Models\Notification;
use Database\DataAccess\DAOFactory;


class FollowDAOImpl implements FollowDAO
{

  public function addFollow(Follow $follow): bool
  {
    $db = DatabaseManager::getMysqliConnection();

    $query = 'INSERT INTO follows (follower_id, followee_id) VALUES (?, ?)';

    $result = $db->prepareAndExecute(
      $query,
      'ii',
      [
        $follow->getFollowId(),
        $follow->getFolloweeId()
      ]
    );

    if ($result) {
      // notificationsテーブルに追加する

      $notificationDAO = DAOFactory::getNotification();

      $notification = new Notification(
        sender_id: $follow->getFollowId(),
        receiver_id: $follow->getFolloweeId(),
        type: 'follow',
      );

      $notificationDAO->insert($notification);

      return true;
    }
    return false;
  }

  public function checkFollow(Follow $follow): bool
  {
    $db = DatabaseManager::getMysqliConnection();

    $query = 'SELECT * FROM follows WHERE follower_id = ? AND followee_id = ?';

    $result = $db->prepareAndFetchAll(
      $query,
      'ii',
      [
        $follow->getFollowId(),
        $follow->getFolloweeId()
      ]
    );

    return (
      count($result) > 0
      && $result[0]['follower_id'] === $follow->getFollowId()
      && $result[0]['followee_id'] === $follow->getFolloweeId()
    );
  }


  public function removeFollow(Follow $follow): bool
  {
    $db = DatabaseManager::getMysqliConnection();

    $query = 'DELETE FROM follows WHERE follower_id = ? AND followee_id = ?';

    $result = $db->prepareAndExecute(
      $query,
      'ii',
      [
        $follow->getFollowId(),
        $follow->getFolloweeId()
      ]
    );

    if ($result) return true;
    return false;
  }

  public function getAllFollowingUser(int $login_user_id): ?array
  {

    $db = DatabaseManager::getMysqliConnection();

    $query =
      'SELECT profiles.*
    FROM follows
    INNER JOIN profiles ON follows.follower_id = profiles.user_id
    WHERE followee_id = ?';

    $result = $db->prepareAndFetchAll(
      $query,
      'i',
      [
        $login_user_id
      ]
    );
    return $result === null ? null : $this->resultsToFollowingUser($result);
  }

  private function resultToFollowingUser(array $result): Profile
  {
    return
      new Profile(
        user_id: $result['user_id'],
        username: $result['username'],
        profile_image_path: $result['profile_image_path']
      );
  }

  private function resultsToFollowingUser(array $results): array
  {
    $data_list = [];
    foreach ($results as $result) {
      $data_list[] = $this->resultToFollowingUser($result);
    }
    return $data_list;
  }

  public function getFollowUserCount(Follow $follow): ?array
  {
    $db = DatabaseManager::getMysqliConnection();

    $query =
    'SELECT count(follows.follower_id) AS follow_count
    FROM follows
    WHERE follower_id = ?
    ';

    $result = $db->prepareAndFetchAll(
      $query,
      'i',
      [$follow->getFollowId()]
    );

    return $result;
  }

  public function getFollowerUserCount(Follow $follow): ?array
  {
    $db = DatabaseManager::getMysqliConnection();

    $query =
    'SELECT count(follows.followee_id) AS follower_count
    FROM follows
    WHERE followee_id = ?
    ';

    $result = $db->prepareAndFetchAll(
      $query,
      'i',
      [$follow->getFolloweeId()]
    );

    return $result;
  }
}
