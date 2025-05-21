<?php

namespace Database\DataAccess\Mappers;

use Models\Post;
use Models\Profile;

class PostMapper
{
    public static function mapRowsToPostDetails(array $rowData): array
    {
      return array_map(function($data) {
            $post = new Post(
                content: $data['content'] ?? '',
                imagePath: $data['post_image_path'] ?? null,
                userId: $data['user_id'] ?? null,
                id: $data['id'] ?? null,
                createdAt: $data['created_at'] ?? null,
                parentPostId: $data['parent_post_id'] ?? null,
            );
            $author = new Profile(
                username: $data['username'] ?? '',
                userId: $data['user_id'] ?? null,
                imagePath: $data['image_path'] ?? null
            );
            return [
                'post' => $post,
                'author' => $author,
                'replyCount' => $data['reply_count'] ?? 0,
                'likeCount' => $data['like_count'] ?? 0,
                'liked' => $data['liked'] ?? 0
            ];
        }, $rowData);
    }
}