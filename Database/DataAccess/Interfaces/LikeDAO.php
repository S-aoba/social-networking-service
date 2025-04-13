<?php

namespace Database\DataAccess\Interfaces;

use Models\Like;

interface LikeDAO
{
    public function createLike(Like $like): bool;

    public function unlike(Like $like): bool;
}