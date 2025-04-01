<?php

namespace Database\DataAccess\Interfaces;

use Models\Like;

interface LikeDAO
{
    public function createLike(Like $like): bool;

    public function unlike(Like $like): bool;

    public function getLikesByUserId(int $userId): array;

    public function getLikesByPostId(int $postId): array;

    public function getAllLikes(): array;
}