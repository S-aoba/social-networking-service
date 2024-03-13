<?php

namespace Database\DataAccess\Implementations;

use Database\DataAccess\Interfaces\PostLikeDAO;
use Database\DatabaseManager;
use Models\PostLike;


class PostLikeDAOImpl implements PostLikeDAO
{
  public function addPostLike(PostLike $postLike): bool
  {

    $db = DatabaseManager::getMysqliConnection();


    $query = "INSERT INTO post_likes (user_id, post_id) VALUES (?, ?)";

    $result = $db->prepareAndExecute(
      $query,
      'ii',
      [
        $postLike->getUserId(),
        $postLike->getPostId(),
      ]
    );

    if ($result) return true;
    return false;
  }

  public function removePostLike(PostLike $postLike): bool
  {

    $db = DatabaseManager::getMysqliConnection();


    $query = "DELETE FROM post_likes WHERE user_id = ? AND post_id = ?";

    $result = $db->prepareAndExecute(
      $query,
      'ii',
      [
        $postLike->getUserId(),
        $postLike->getPostId(),
      ]
    );

    if ($result) return true;
    return false;
  }
}
