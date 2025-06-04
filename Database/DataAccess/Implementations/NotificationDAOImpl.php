<?php

namespace Database\DataAccess\Implementations;

use Database\DataAccess\Interfaces\NotificationDAO;
use Database\DatabaseManager;
use Exception;
use Models\DataTimeStamp;
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

        $encodedData = json_encode($notification->getData());
        $result = $mysqli->prepareAndExecute($query, 'iss', [
          $notification->getUserId(),
          $notification->getType(),
          $encodedData
        ]);

        if ($result === false) {
            return false;
        }

        $notification->setId($mysqli->insert_id);

        return true;
    }

    public function hasNotification(int $userId): bool
    {
        $hasNotification = $this->checkNotification($userId);
        
        return $hasNotification;
    }

    private function checkNotification(int $userId): bool
    {
        $mysqli = DatabaseManager::getMysqliConnection();

        $query = "SELECT COUNT(*) AS notification_count FROM notifications WHERE user_id = ?";

        $result = $mysqli->prepareAndFetchAll($query, 'i', [$userId]);
        
        return $result[0]['notification_count'];
    }

    public function getNotification(int $userId): ?array
    {
        $notificationRow = $this->fetchNotification($userId);

        if($notificationRow === null) return null;

        return $this->rowDataToNotifications($notificationRow);
    }

    private function fetchNotification(int $userId): ?array
    {
        $mysqli = DatabaseManager::getMysqliConnection();

        $query = "SELECT * FROM notifications WHERE user_id = ?";

        $result = $mysqli->prepareAndFetchAll($query, 'i', [$userId]);
        
        if(empty($result)) return null;

        return $result;
    }

    private function rowDataToNotifications(array $rowData): array
    {
        $notifications = [];

        foreach ($rowData as $data) {
            $decodedData = json_decode($data['data'], true);
            
            $notifications[] = new Notification(
                userId: $data['user_id'],
                type: $data['type'],
                id: $data['id'],
                data: $decodedData,
                readAt: $data['read_at'],
                timestamp: new DataTimeStamp($data['created_at'], $data['updated_at'])
            );
        }

        return $notifications;
    }
}
