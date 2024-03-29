<?php

namespace Database\DataAccess\Implementations;

use Database\DataAccess\Interfaces\PostLikeDAO;
use Database\DatabaseManager;
use Models\PostLike;
use Models\Notification;
use Database\DataAccess\DAOFactory;

class PostLikeDAOImpl implements PostLikeDAO
{
  public function addPostLike(PostLike $postLike, string $content): bool
  {

    $db = DatabaseManager::getMysqliConnection();


    $query = "INSERT INTO post_likes (user_id, post_id, post_user_id) VALUES (?, ?, ?)";

    $result = $db->prepareAndExecute(
      $query,
      'iii',
      [
        $postLike->getUserId(),
        $postLike->getPostId(),
        $postLike->getPostUserId(),
      ]
    );

    if ($result) {
      $notificationDAO = DAOFactory::getNotification();

      $notification = new Notification(
        sender_id: $_SESSION['user_id'],
        receiver_id: $postLike->getPostUserId(),
        type: 'like',
        content: $content
      );

      $notificationDAO->insert($notification);

      return true;
    }
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
