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

    public function getNotification(int $id): ?Notification
    {
        $notificationRow = $this->fetchNotification($id);

        if ($notificationRow === null) {
            return null;
        }

        return $this->rowDataToNotification($notificationRow[0]);
    }

    private function fetchNotification(int $id): ?array
    {
        $mysqli = DatabaseManager::getMysqliConnection();

        $query = "SELECT * FROM notifications WHERE id = ?";

        $result = $mysqli->prepareAndFetchAll($query, 'i', [$id]);

        if (empty($result)) {
            return null;
        }

        return $result;
    }

    public function markAsRead(int $id): bool
    {
        $mysqli = DatabaseManager::getMysqliConnection();

        $query = "UPDATE notifications SET read_at = NOW() WHERE id = ?";

        $result = $mysqli->prepareAndExecute($query, 'i', [$id]);

        return $result !== false;
    }

    public function hasNotification(int $userId): int
    {
        $hasNotification = $this->checkNotification($userId);

        return $hasNotification;
    }

    private function checkNotification(int $userId): int
    {
        $mysqli = DatabaseManager::getMysqliConnection();

        $query = "SELECT COUNT(*) AS notification_count FROM notifications WHERE user_id = ? AND read_at IS NULL";

        $result = $mysqli->prepareAndFetchAll($query, 'i', [$userId]);

        return $result[0]['notification_count'];
    }

    public function getAllNotifications(int $userId): ?array
    {
        $notificationRow = $this->fetchAllNotifications($userId);

        if ($notificationRow === null) {
            return null;
        }

        return $this->rowDataToNotifications($notificationRow);
    }

    private function fetchAllNotifications(int $userId): ?array
    {
        $mysqli = DatabaseManager::getMysqliConnection();

        $query = "SELECT * FROM notifications WHERE user_id = ? AND read_at IS NULL ORDER BY created_at DESC";

        $result = $mysqli->prepareAndFetchAll($query, 'i', [$userId]);

        if (empty($result)) {
            return null;
        }

        return $result;
    }

    private function rowDataToNotifications(array $rowData): array
    {
        $notifications = [];

        foreach ($rowData as $data) {
            $notifications[] = $this->rowDataToNotification($data);
        }

        return $notifications;
    }

    private function rowDataToNotification(array $rowData): ?Notification
    {
        $decodedData = json_decode($rowData['data'], true);

        return new Notification(
            userId: $rowData['user_id'],
            type: $rowData['type'],
            id: $rowData['id'],
            data: $decodedData,
            readAt: $rowData['read_at'],
            timestamp: new DataTimeStamp($rowData['created_at'], $rowData['updated_at'])
        );
    }
}
