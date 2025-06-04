<?php

namespace Database\DataAccess\Implementations;

use Database\DataAccess\Interfaces\NotificationDAO;
use Database\DatabaseManager;
use Exception;
use Models\Notification;

class NotificationDAOImpl implements NotificationDAO
{
    public function notifyUser(Notification $notification): bool
    {
        if ($notification->getId() !== null) {
            throw new Exception("Cannnot create a notification with an existing ID. id: {$notification->getId()}");
        }

        $mysqli = DatabaseManager::getMysqliConnection();

        $query = "INSERT INTO notifications (user_id, type, data) VALUES (?, ?, ?)";

        $result = $mysqli->prepareAndExecute($query, 'iss', [
          $notification->getUserId(),
          $notification->getType(),
          $notification->getData()
        ]);

        if ($result === false) {
            return false;
        }

        $notification->setId($mysqli->insert_id);

        return true;
    }

    public function getNotification(int $userId): ?array
    {
        $notificationRow = $this->fetchNotification($userId);

        if($notificationRow === null) return null;

        return $notificationRow;
    }

    private function fetchNotification(int $userId): ?array
    {
        $mysqli = DatabaseManager::getMysqliConnection();

        $query = "SELECT * FROM notifications WHERE user_id = ?";

        $result = $mysqli->prepareAndFetchAll($query, 'i', [$userId]);

        if(empty($result)) return null;

        error_log(var_export($result,true));

        return [];
    }
}
