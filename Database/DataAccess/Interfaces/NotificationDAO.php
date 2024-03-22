<?php

namespace Database\DataAccess\Interfaces;

use Models\Notification;

interface NotificationDAO
{
  public function insert(Notification $notification): bool;
  public function update(Notification $notification): bool;
  public function delete(Notification $notification): bool;
  public function get(int $id): ?Notification;
  public function getAll(): array;
}
