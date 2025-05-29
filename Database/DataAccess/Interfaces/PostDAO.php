<?php

namespace Database\DataAccess\Interfaces;

use Models\Post;

interface PostDAO
{
    public function create(Post $post): bool;
    public function getById(int $postId, int $userId): ?array;
    public function getByUserId(int $userId): ?array;
    public function getFollowingPosts(int $userId): ?array;
    public function getReplies(int $parentPostId, int $userId): ?array;
    public function deletePost(int $postId): bool;

    public function findParentPost(int $postId): ?Post;
}
