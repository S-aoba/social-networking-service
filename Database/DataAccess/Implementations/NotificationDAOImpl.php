<?php

namespace Database\DataAccess\Implementations;

use Database\DataAccess\Interfaces\NotificationDAO;
use Database\DatabaseManager;
use Models\Notification;


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

  public function getById(int $id): ?Notification
  {
    return null;
  }

  public function getAll(): array
  {
    return [];
  }
}
