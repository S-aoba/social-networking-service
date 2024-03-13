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

  public function getLikeCountByPostId(int $post_id): array
  {
    $db = DatabaseManager::getMysqliConnection();

    $query = "SELECT COUNT(*) FROM post_likes WHERE post_id = ?";

    $result = $db->prepareAndFetchAll(
      $query,
      'i',
      [
        $post_id
      ]
    );

    return $result ?? [];
  }

  public function getLikeByUserId(int $user_id, int $post_id): bool
  {
    $db = DatabaseManager::getMysqliConnection();

    $query = "SELECT * FROM post_likes WHERE user_id = ? AND post_id = ?";

    $result = $db->prepareAndFetchAll(
      $query,
      'ii',
      [
        $user_id,
        $post_id
      ]
    );
    return (
      count($result) > 0
      && $result[0]['user_id'] === $user_id
      && $result[0]['post_id'] === $post_id);
  }
}
