<?php

namespace Database\DataAccess\Implementations;

use Database\DataAccess\Interfaces\FollowDAO;
use Database\DatabaseManager;
use Models\Follow;


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

    if ($result) return true;
    return false;
  }

  public static function checkFollow(Follow $follow): bool
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
}
