<?php

namespace Database\DataAccess\Interfaces;

use Models\Follow;

interface FollowDAO
{
  public function follow(int $userId, int $followUserId): bool;
  public function unfollow(int $userId, int $followUserId): bool;
  public function getFollowerList($userId): ?Follow;
  public function getFollowingList($userId): ?Follow;
}