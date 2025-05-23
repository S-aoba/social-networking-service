<?php

namespace Database\DataAccess\Interfaces;

use Models\Like;

interface LikeDAO
{
    public function like(Like $like): bool;

    public function unlike(Like $like): bool;

    public function hasLiked(Like $like): bool;

    public function getLikeCount(Like $like): int;
}