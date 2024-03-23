<?php

namespace Database\DataAccess\Implementations;

use Database\DataAccess\Interfaces\NotificationDAO;
use Database\DatabaseManager;
use Models\Notification;
use Models\Profile;


class NotificationDAOImpl implements NotificationDAO
{
  public function insert(Notification $notification): bool
  {
    $db = DatabaseManager::getMysqliConnection();

    $query = "INSERT INTO notifications (sender_id, receiver_id, type, content) VALUES (?, ?, ?, ?)";

    $result = $db->prepareAndExecute(
      $query,
      'iiss',
      [
        $notification->getSenderId(),
        $notification->getReceiverId(),
        $notification->getType(),
        $notification->getContent()
      ]
    );

    if ($result === false) return false;

    return true;
  }

  public function update(Notification $notification): bool
  {
    return true;
  }

  public function delete(Notification $notification): bool
  {
    return true;
  }

  public function getById(int $id): ?array
  {
    $db = DatabaseManager::getMysqliConnection();

    $query =
      "SELECT notifications.*, profiles.username, profiles.profile_image_path, profiles.user_id, profiles.created_at AS profile_created_at
    FROM notifications
    INNER JOIN profiles ON notifications.sender_id = profiles.user_id
    WHERE receiver_id = ?
    ";

    $result = $db->prepareAndFetchAll(
      $query,
      'i',
      [$id]
    );

    return $result === null ? null : $this->resultsToNotifications($result);
  }

  public function getAll(): array
  {
    return [];
  }

  private function resultNotification(array $result): array
  {
    return [
      'notification' =>
      new Notification(
        sender_id: $result['sender_id'],
        receiver_id: $result['receiver_id'],
        type: $result['type'],
        content: $result['content'],
        created_at: $result['created_at']
      ),
      'profile' =>
      new Profile(
        user_id: $result['user_id'],
        username: $result['username'],
        profile_image_path: $result['profile_image_path']
      )
    ];
  }
  private function resultsToNotifications(array $results): array
  {
    $data_list = [];
    foreach ($results as $result) {
      $data_list[] = $this->resultNotification($result);
    }
    return $data_list;
  }

  public function toggleReadStatus(int $receiver_id): bool
  {
    $db = DatabaseManager::getMysqliConnection();

    $query =
      'UPDATE notifications
    SET read_status = 1
    WHERE receiver_id = ? AND read_status = 0
    ';

    $result = $db->prepareAndExecute(
      $query,
      'i',
      [$receiver_id]
    );

    if ($result === true) return true;

    return false;
  }

  public function checkIsNotificationExists(int $receiver_id): bool
  {
    $db = DatabaseManager::getMysqliConnection();

    $query =
      'SELECT count(*) AS notifications_count
    FROM notifications
    WHERE receiver_id = ? AND read_status = 0
    ';

    $result = $db->prepareAndFetchAll(
      $query,
      'i',
      [$receiver_id]
    );
    return $result[0]['notifications_count'] > 0;
  }
}
