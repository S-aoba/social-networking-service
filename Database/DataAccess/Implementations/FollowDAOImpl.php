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

    $query = 'INSERT INTO follows (follow_id, followee_id) VALUES (?, ?)';

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


  public function removeFollow(Follow $follow): bool
  {
    $db = DatabaseManager::getMysqliConnection();

    $query = 'DELETE FROM follows WHERE follow_id = ? AND followee_id = ?';

    $result = $db->prepareAndExecute(
      $query,
      'ii',
      [
        $follow->getFollowId(),
        $follow->getFolloweeId()
      ]
    );

    if ($result) {
      return true;
    } else {
      return false;
    }
  }
}
