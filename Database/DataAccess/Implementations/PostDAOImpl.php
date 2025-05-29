<?php

namespace Database\DataAccess\Implementations;

use Database\DatabaseManager;
use Database\DataAccess\Interfaces\PostDAO;
use Database\DataAccess\Mappers\PostMapper;
use Models\Post;

class PostDAOImpl implements PostDAO
{
    public function create(Post $post): bool
    {
        if ($post->getId() !== null) {
            throw new \Exception('Cannnot create a post with an existing ID. id: ' . $post->getId());
        }

        $mysqli = DatabaseManager::getMysqliConnection();

        $query = "INSERT INTO posts (content, image_path, user_id, parent_post_id) VALUES (?, ?, ?, ?)";

        $result = $mysqli->prepareAndExecute(
            $query,
            'ssii',
            [
            $post->getContent(),
            $post->getImagePath(),
            $post->getUserId(),
            $post->getParentPostId()
        ]
        );

        if ($result === false) {
            return false;
        }

        $post->setId($mysqli->insert_id);

        return true;
    }

    public function getFollowingPosts(int $userId): ?array
    {
        $rowFollowingPosts = $this->fetchFollowingPosts($userId);
        if ($rowFollowingPosts === null) {
            return null;
        }

        return PostMapper::toPostDetails($rowFollowingPosts);
    }

    private function fetchFollowingPosts(int $userId): ?array
    {
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
                    SELECT EXISTS(
                      SELECT 1
                      FROM likes
                      WHERE post_id = posts.id AND user_id = ?
                    )
                  ) AS liked
                FROM posts
                JOIN profiles ON posts.user_id = profiles.user_id
                WHERE posts.user_id = ? 
                  OR posts.user_id IN (SELECT following_id FROM follows WHERE follower_id = ?)
                ORDER BY posts.created_at DESC
                LIMIT 10;
                ";
        $result = $mysqli->prepareAndFetchAll($query, 'iii', [$userId, $userId, $userId]);

        if (empty($result)) {
            return null;
        }
        return $result;
    }

    public function getById(int $postId, int $userId): ?array
    {
        $rowPost = $this->fetchById($postId, $userId);

        if ($rowPost === null) {
            return null;
        }

        return PostMapper::toPostDetails($rowPost)[0];
    }

    private function fetchById(int $postId, int $userId): ?array
    {
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
                    SELECT EXISTS(
                      SELECT 1
                      FROM likes
                      WHERE post_id = posts.id AND user_id = ?
                    )
                  ) AS liked
                FROM posts 
                JOIN profiles ON posts.user_id = profiles.user_id
                WHERE posts.id = ?
              ";

        $result = $mysqli->prepareAndFetchAll($query, 'ii', [$userId, $postId]);

        if (empty($result)) {
            return null;
        }

        return $result;
    }

    public function getReplies(int $parentPostId, int $userId): ?array
    {
        $repliesRow = $this->fetchReplies($parentPostId, $userId);

        if ($repliesRow === null) {
            return null;
        }

        return PostMapper::toPostDetails($repliesRow);
    }

    private function fetchReplies(int $parentPostId, int $userId): ?array
    {
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
                      SELECT EXISTS(
                        SELECT 1
                        FROM likes
                        WHERE post_id = posts.id AND user_id = ?
                      )
                    ) AS liked
                FROM posts
                JOIN profiles ON posts.user_id = profiles.user_id
                WHERE posts.parent_post_id = ?
                ORDER BY posts.created_at DESC;
              ";

        $result = $mysqli->prepareAndFetchAll($query, 'ii', [$userId, $parentPostId]);
        if (empty($result)) {
            return null;
        }

        return $result;
    }

    public function getByUserId(int $userId): ?array
    {
        $postRow = $this->fetchByUserId($userId);

        if ($postRow === null) {
            return null;
        }

        return PostMapper::toOwnPosts($postRow);
    }

    private function fetchByUserId(int $userId): ?array
    {
        $mysqli = DatabaseManager::getMysqliConnection();

        $query = "SELECT
                posts.id AS post_id,
                posts.user_id AS post_user_id,
                posts.image_path AS post_image_path,
                posts.created_at,
                posts.parent_post_id,
                posts.content,

                p.id AS authro_id,
                p.user_id AS author_user_id,
                p.image_path AS author_image_path,
                p.username,
                p.address,
                p.age,
                p.hobby,
                p.self_introduction,
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
                  SELECT EXISTS(
                    SELECT 1
                    FROM likes
                    WHERE post_id = posts.id AND user_id = ?
                  )
                ) AS liked
                FROM posts
                JOIN profiles p ON p.user_id = posts.user_id
                WHERE posts.user_id = ? 
                ORDER BY posts.created_at DESC 
                LIMIT 10";

        $result = $mysqli->prepareAndFetchAll($query, 'ii', [$userId, $userId]);
        if (empty($result)) {
            return null;
        }

        return $result;
    }

    public function findParentPost(int $postId): ?Post
    {
        $rowPost = $this->fetchParentPost($postId);

        if ($rowPost === null) {
            return null;
        }

        return PostMapper::toPost($rowPost);
    }

    private function fetchParentPost(int $postId): ?array
    {
        $mysqli = DatabaseManager::getMysqliConnection();

        $query = "SELECT *
                FROM posts
                WHERE id = ?
              ";

        $result = $mysqli->prepareAndFetchAll($query, 'i', [$postId]);

        if (empty($result)) {
            return null;
        }

        return $result;
    }

    public function deletePost(int $postId): bool
    {
        $mysqli = DatabaseManager::getMysqliConnection();

        $query = "DELETE FROM posts WHERE id = ?";

        $result = $mysqli->prepareAndExecute($query, 'i', [$postId]);

        if ($result === false) {
            return false;
        }

        return true;
    }
}
