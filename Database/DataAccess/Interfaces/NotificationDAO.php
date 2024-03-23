<?php

namespace Database\DataAccess\Interfaces;

use Models\Notification;

interface NotificationDAO
{
  public function insert(Notification $notification): bool;
  public function update(Notification $notification): bool;
  public function delete(Notification $notification): bool;
  public function getById(int $id): ?array;
  public function getAll(): array;
  public function toggleReadStatus(int $receiver_id): bool;
  public function checkIsNotificationExists(int $receiver_id): bool;
}
