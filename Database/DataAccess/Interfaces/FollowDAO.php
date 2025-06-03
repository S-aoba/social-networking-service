<?php

namespace Database\DataAccess\Interfaces;

interface FollowDAO
{
    public function follow(int $userId, int $followUserId): bool;
    public function unfollow(int $userId, int $followUserId): bool;
    public function getFollowerCount($userId): int;
    public function getFollowingCount($userId): int;
    public function getFollowing(int $userId): ?array;
    public function getFollower(int $userId): ?array;
    public function isFollowingSelf(int $userId, int $followingId): bool;
    public function isMutualFollow(int $userId, int $partnerId): bool;
}
