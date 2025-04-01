<?php

namespace Database\DataAccess\Interfaces;

interface LikeDAO
{
    public function createLike(Like $like): bool;

    public function deleteLike(int $id): bool;

    public function getLikesByUserId(int $userId): array;

    public function getLikesByPostId(int $postId): array;

    public function getAllLikes(): array;
}