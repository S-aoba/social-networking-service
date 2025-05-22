<?php

namespace Database\DataAccess\Mappers;

use Models\Post;
use Models\Profile;

class PostMapper
{
    /**
     * 投稿＋著者情報＋カウント類を含む配列へ変換
     * @param array $rowData DBから取得した連想配列の配列
     * @return array<int, array{post: Post, author: Profile, replyCount: int, likeCount: int, liked: int}>
     */
    public static function toPostDetails(array $rowData): array
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

    /**
     * 自分の投稿一覧（著者情報なし）へ変換
     * @param array $rowData DBから取得した連想配列の配列
     * @return array<int, array{post: Post, replyCount: int, likeCount: int, liked: int}>
     */
    public static function toOwnPosts(array $rowData): array
    {
        return array_map(function($data) {
            $post = new Post(
                content: $data['content'] ?? '',
                imagePath: $data['post_image_path'] ?? null,
                userId: $data['post_user_id'] ?? null,
                id: $data['post_id'] ?? null,
                createdAt: $data['created_at'] ?? null,
                parentPostId: $data['parent_post_id'] ?? null,
            );

            $profile = new Profile(
                username: $data['username'],
                userId: $data['author_user_id'],
                imagePath: $data['author_image_path'],
                address: $data['address'],
                age: $data['age'],
                hobby: $data['hobby'],
                selfIntroduction: $data['self_introduction']
            );

            return [
                'post' => $post,
                'author' => $profile,
                'replyCount' => $data['reply_count'] ?? 0,
                'likeCount' => $data['like_count'] ?? 0,
                'liked' => $data['liked'] ?? 0
            ];
        }, $rowData);
    }

    /**
     * 1件分の配列データをPostオブジェクトに変換
     * @param array $rowData DBから取得した1件または1件のみの配列
     * @return Post|null
     */
    public static function toPost(array $rowData): ?Post
    {
        $data = is_array($rowData[0] ?? null) ? $rowData[0] : $rowData;

        return new Post(
            content: $data['content'] ?? '',
            userId: $data['user_id'] ?? null,
            id: $data['id'] ?? null,
            imagePath: $data['image_path'] ?? null,
            parentPostId: $data['parent_post_id'] ?? null,
            createdAt: $data['created_at'] ?? null
        );
    }
}