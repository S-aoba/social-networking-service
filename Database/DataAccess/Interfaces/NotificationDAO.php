<?php

namespace Database\DataAccess\Interfaces;

use Models\Notification;

interface NotificationDAO
{
    public function notifyUser(Notification $notification): bool;
    public function getAllNotifications(int $userId): ?array;
    public function getNotification(int $id): ?Notification;
    public function hasNotification(int $userId): bool;
    public function markAsRead(int $id): bool;
}
