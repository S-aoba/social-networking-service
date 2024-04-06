<?php

namespace Database\DataAccess\Implementations;

use Database\DataAccess\Interfaces\ReplyDAO;
use Database\DatabaseManager;
use Models\DataTimeStamp;
use Models\Reply;
use Models\Profile;
use Helpers\FileHelper;


class ReplyDAOImpl implements ReplyDAO
{
  public function createReply(Reply $reply): bool
  {
    $db = DatabaseManager::getMysqliConnection();

    $query = 'INSERT INTO replies (content, user_id, post_id) VALUES (?, ?, ?)';

    $result = $db->prepareAndExecute(
      $query,
      'sii',
      [
        $reply->getContent(),
        $reply->getUserId(),
        $reply->getPostId(),
      ]
    );

    $reply_id = $db->insert_id;
    $reply->setId($reply_id);


    if ($result) return true;
    return false;
  }

  public function deleteReply(int $id): bool
  {

    $db = DatabaseManager::getMysqliConnection();

    $query = 'DELETE FROM replies WHERE id = ?';

    $result = $db->prepareAndExecute($query, 'i', [$id]);

    if ($result) return true;
    return false;
  }

  public function getReplyByPostId(int $postId): ?array
  {

    $db = DatabaseManager::getMysqliConnection();

    $query = 'SELECT replies.*, profiles.*, replies.user_id AS reply_user_id, profiles.user_id AS profile_user_id, replies.created_at AS reply_created_at, profiles.created_at AS profile_created_at, replies.updated_at AS reply_updated_at, profiles.updated_at AS profile_updated_at, replies.id AS reply_id
    FROM replies
    JOIN profiles ON replies.user_id = profiles.user_id
    WHERE replies.post_id = ?
    ';

    $result = $db->prepareAndFetchAll($query, 'i', [$postId]) ?? null;

    if(count($result) === 0) return null;
    return $this->resultsToReply($result);
  }

  public function getReplyCountForPost(int $postId): int
  {

    $db = DatabaseManager::getMysqliConnection();

    $query = 'SELECT COUNT(*) FROM replies WHERE post_id = ?';
    $result = $db->prepareAndFetchAll($query, 'i', [$postId])[0] ?? null;
    if ($result === null) return 0;
    return $result['COUNT(*)'];
  }


  private function resultToReply(array $results): array
  {
    return [
      'reply' =>
      new Reply(
        $results['content'],
        $results['user_id'],
        $results['post_id'],
        $results['reply_id'],
        $results['parent_reply_id'],
        $results['status'],
        $results['deleted_at'],
        new DataTimeStamp($results['reply_created_at'], $results['reply_updated_at'])
      ),
      'reply_user_profile' =>
      new Profile(
        user_id: $results['profile_user_id'],
        id: $results['id'],
        username: $results['username'],
        age: $results['age'],
        address: $results['address'],
        hobby: $results['hobby'],
        self_introduction: $results['self_introduction'],
        profile_image_path: $results['profile_image_path'],
        timeStamp: new DataTimeStamp($results['profile_created_at'], $results['profile_updated_at'])
      )

    ];
  }

  private function resultsToReply(array $results): array
  {
    $data_list = [];
    foreach ($results as $result) {
      $data_list[] = $this->resultToReply($result);
    }
    return $data_list;
  }
}
