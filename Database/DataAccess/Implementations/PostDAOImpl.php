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

      $query = "INSERT INTO posts (content, image_path ,user_id, parent_post_id) VALUES (?, ?, ?, ?)";

      $result = $mysqli->prepareAndExecute(
        $query,
        'sii',
        [
          $post->getContent(),
          $post->getImagePath(),
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

    public function deletePost(int $postId): bool
    {
      $mysqli = DatabaseManager::getMysqliConnection();

      $query = "DELETE FROM posts WHERE id = ?";

      $result = $mysqli->prepareAndExecute($query, 'i', [$postId]);

      if($result === false) return false;

      return true;
    }

    private function getRowFollowingPosts(int $userId): ?array {
      $mysqli = DatabaseManager::getMysqliConnection();

      $query = "SELECT 
                  posts.*, 
                  posts.image_path AS post_image_path,
                  profiles.username, 
                  profiles.image_path, 
                  profiles.user_id,
                  (
                        SELECT COUNT(*) 
                        FROM posts AS child 
                        WHERE child.parent_post_id = posts.id
                    ) AS reply_count,
                    (
                        SELECT COUNT(*) 
                        FROM likes 
                        WHERE post_id = posts.id
                    ) AS like_count,
                    (
                        SELECT COUNT(*) 
                        FROM likes 
                        WHERE post_id = posts.id AND user_id = ?
                    ) AS liked
                FROM posts
                JOIN profiles ON posts.user_id = profiles.user_id
                WHERE posts.user_id = ? 
                  OR posts.user_id IN (SELECT following_id FROM follows WHERE follower_id = ?)
                ORDER BY posts.created_at DESC
                LIMIT 10;
                ";
      $result = $mysqli->prepareAndFetchAll($query, 'iii', [$userId, $userId, $userId]) ?? null;
      if($result === null) return null;

      return $this->rowDataToPost($result);
    }

    private function rowDataToPost(?array $rowData): ?array {
      $output = [];

      foreach ($rowData as $data) {
        $post = new Post(
         content: $data['content'],
        imagePath: $data['post_image_path'],
         userId: $data['user_id'],
         id: $data['id'],
         createdAt: $data['created_at'],
         parentPostId: $data['parent_post_id'],
        );
        $postedUser = new Profile(
          username: $data['username'],
          userId: $data['user_id'],
          imagePath: $data['image_path']
        );

        $arr = [
          'post' => $post,
          'postedUser' => $postedUser,
          'replyCount' => $data['reply_count'],
          'likeCount' => $data['like_count'],
          'like' => $data['liked'],
        ];

        $output[] = $arr;
      }

      return $output;
    }

    public function getById(int $postId, int $userId): ?array
    {
      $postRow = $this->getRowById($postId, $userId);

      if($postRow === null) return null;

      return $postRow[0];
    }

    public function getReplies(int $parentPostId, int $userId): ?array
    {
      $repliesRow = $this->getRowByParentPostId($parentPostId, $userId);

      if($repliesRow === null) return null;

      return $repliesRow;
    }
    
    private function getRowByParentPostId(int $parentPostId, int $userId): ?array {
      $mysqli = DatabaseManager::getMysqliConnection();

      $query = "SELECT 
                    posts.*, 
                    posts.image_path AS post_image_path,
                    profiles.username, 
                    profiles.image_path, 
                    profiles.user_id,
                    (
                        SELECT COUNT(*) 
                        FROM posts AS child 
                        WHERE child.parent_post_id = posts.id
                    ) AS reply_count,
                    (
                        SELECT COUNT(*) 
                        FROM likes 
                        WHERE post_id = posts.id
                    ) AS like_count,
                    (
                        SELECT COUNT(*) 
                        FROM likes 
                        WHERE post_id = posts.id AND user_id = ?
                    ) AS liked
                FROM posts
                JOIN profiles ON posts.user_id = profiles.user_id
                WHERE posts.parent_post_id = ?
                ORDER BY posts.created_at DESC;
              ";

      $result = $mysqli->prepareAndFetchAll($query, 'ii', [$userId, $parentPostId]);
      if(count($result) === 0) return null;

      return $this->rowDataToPost($result);
    }

    private function getRowById(int $postId, int $userId): ?array {
      $mysqli = DatabaseManager::getMysqliConnection();

      $query = "SELECT 
                  posts.*, 
                  posts.image_path AS post_image_path,
                  profiles.username, 
                  profiles.image_path, 
                  profiles.user_id,
                  (
                        SELECT COUNT(*) 
                        FROM posts AS child 
                        WHERE child.parent_post_id = posts.id
                    ) AS reply_count,
                    (
                        SELECT COUNT(*) 
                        FROM likes 
                        WHERE post_id = posts.id
                    ) AS like_count,
                    (
                        SELECT COUNT(*) 
                        FROM likes 
                        WHERE post_id = posts.id AND user_id = ?
                    ) AS liked
                FROM posts 
                JOIN profiles ON posts.user_id = profiles.user_id
                WHERE posts.id = ?
              ";

      $result = $mysqli->prepareAndFetchAll($query, 'ii', [$userId, $postId]);

      if(count($result) === 0) return null;

      return $this->rowDataToPost($result);
    }

    public function getByUserId(int $userId): ?array
    {
      $postRow = $this->getRowByUserId($userId);

      if($postRow === null) return null;

      return $postRow;
    }

    private function getRowByUserId(int $userId): ?array {
      $mysqli = DatabaseManager::getMysqliConnection();

      $query = "SELECT * ,
                posts.image_path AS post_image_path,
                (
                    SELECT COUNT(*) 
                    FROM posts AS child 
                    WHERE child.parent_post_id = posts.id
                ) AS reply_count,
                (
                        SELECT COUNT(*) 
                        FROM likes 
                        WHERE post_id = posts.id
                    ) AS like_count,
                    (
                        SELECT COUNT(*) 
                        FROM likes 
                        WHERE post_id = posts.id AND user_id = ?
                    ) AS liked
                FROM posts 
                WHERE user_id = ? 
                ORDER BY posts.created_at DESC 
                LIMIT 10";

      $result = $mysqli->prepareAndFetchAll($query, 'ii', [$userId, $userId]);
      if(count($result) === 0) return null;

      return $this->rowDataToOwnPost($result);
    }

    private function rowDataToOwnPost(array $rowData): array {
      $output = [];

      foreach($rowData as $data) {
        $post = new Post(
          content: $data['content'],
          imagePath: $data['post_image_path'],
          userId: $data['user_id'],
          id: $data['id'],
          createdAt: $data['created_at'],
          parentPostId: $data['parent_post_id'],
        );

        $arr = [
          'post' => $post,
          'replyCount' => $data['reply_count'],
          'likeCount' => $data['like_count'],
          'like' => $data['liked'],
        ]; 
        $output[] = $arr;
      }

      return $output;
    }
}