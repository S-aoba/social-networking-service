<?php

namespace Database\DataAccess\Implementations;

use Database\DataAccess\Interfaces\PostDAO;
use Database\DatabaseManager;
use Models\Post;
use Models\Profile;

class PostDAOImpl implements PostDAO
{
    public function create(Post $post): bool
    {
      if($post->getId() !== null) throw new \Exception('Cannnot create a post with an existing ID. id: ' . $post->getId());

      $mysqli = DatabaseManager::getMysqliConnection();

      $query = "INSERT INTO posts (content, user_id, parent_post_id) VALUES (?, ?, ?)";

      $result = $mysqli->prepareAndExecute(
        $query,
        'sii',
        [
          $post->getContent(),
          $post->getUserId(),
          $post->getParentPostId()
        ]
      );

      if($result === false) return false;

      $post->setId($mysqli->insert_id);

      return true;
    }

    public function getFollowingPosts(int $userId): ?array
    {
      $followerPostsRow = $this->getRowFollowingPosts($userId);
      return $followerPostsRow;
    }

    private function getRowFollowingPosts(int $userId): ?array {
      $mysqli = DatabaseManager::getMysqliConnection();

      $query = "SELECT posts.id, posts.content, posts.user_id, profiles.username, profiles.    image_path, profiles.user_id
                FROM posts
                JOIN follows ON posts.user_id = follows.following_id
                JOIN profiles ON posts.user_id = profiles.user_id
                WHERE follows.follower_id = ?
                ORDER BY posts.created_at DESC
                LIMIT 10
                ";
      $result = $mysqli->prepareAndFetchAll($query, 'i', [$userId]) ?? null;

      if($result === null) return null;

      return $this->rowDataToPost($result);
    }

    private function rowDataToPost(?array $rowData): ?array {
      $output = [];

      foreach ($rowData as $data) {
        $post = new Post(
         content: $data['content'],
         userId: $data['user_id'],
         id: $data['id']
        );
        $postedUser = new Profile(
          username: $data['username'],
          userId: $data['user_id'],
          imagePath: $data['image_path']
        );

        $arr = [
          'post' => $post,
          'postedUser' => $postedUser
        ];

        $output[] = $arr;
      }

      return $output;
    }

    public function getByUserId(int $userId): ?Post
    {
      return null;
    }
}