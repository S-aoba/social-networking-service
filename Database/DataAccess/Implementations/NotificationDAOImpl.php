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
}
