<?php

namespace Database\DataAccess\Interfaces;

use Models\Follow;

interface FollowDAO
{
  public function follow(int $userId, int $followUserId): bool;
  public function unFollow(int $userId, int $followUserId): bool;
  public function getFollowList($userId): ?Follow;
  public function getFollowerList($userId): ?Follow;
}