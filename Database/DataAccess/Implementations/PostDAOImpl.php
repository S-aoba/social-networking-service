<?php

namespace Database\DataAccess\Implementations;

use Database\DataAccess\Interfaces\PostDAO;
use Database\DatabaseManager;
use Models\Post;

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

      $query = "SELECT posts.content
                FROM posts
                JOIN follows ON posts.user_id = follows.following_id
                WHERE follows.follower_id = ?
                ORDER BY posts.created_at DESC
                ";
      $result = $mysqli->prepareAndFetchAll($query, 'i', [$userId]) ?? null;

      if($result === null) return null;

      return $result;
    }

    public function getByUserId(int $userId): ?Post
    {
      return null;
    }
}