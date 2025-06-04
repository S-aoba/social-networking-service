<?php

namespace Database\DataAccess\Interfaces;

use Models\Notification;

interface NotificationDAO
{
    public function notifyUser(Notification $notification): bool;
    public function getNotification(int $userId): ?array;
}
