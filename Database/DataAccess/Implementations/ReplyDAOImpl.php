<?php

namespace Database\DataAccess\Implementations;

use Database\DataAccess\Interfaces\ReplyDAO;
use Database\DatabaseManager;
use Helpers\FileHelper;
use Models\DataTimeStamp;
use Models\Reply;
use Models\Profile;


class ReplyDAOImpl implements ReplyDAO
{
  public function createReply(Reply $reply): bool
  {
    $db = DatabaseManager::getMysqliConnection();

    $query = 'INSERT INTO replies (content, user_id, post_id, file_path, file_type) VALUES (?, ?, ?, ?, ?)';

    $result = $db->prepareAndExecute(
      $query,
      'siiss',
      [
        $reply->getContent(),
        $reply->getUserId(),
        $reply->getPostId(),
        $reply->getFilePath(),
        $reply->getFileType()
      ]
    );

    $reply_id = $db->insert_id;
    $reply->setId($reply_id);


    if ($result) return true;
    return false;
  }

  public function deleteReply(int $reply_id): bool
  {

    $db = DatabaseManager::getMysqliConnection();

    $query = 'DELETE FROM replies WHERE reply_id = ?';

    $result = $db->prepareAndExecute($query, 'i', [$reply_id]);

    if ($result) return true;
    return false;
  }

  public function getReplyByPostId(int $postId): ?array
  {

    $db = DatabaseManager::getMysqliConnection();

    $query = 'SELECT replies.*, profiles.*, replies.user_id AS reply_user_id, profiles.user_id AS profile_user_id, replies.created_at AS reply_created_at, profiles.created_at AS profile_created_at, replies.updated_at AS reply_updated_at, profiles.updated_at AS profile_updated_at
    FROM replies
    JOIN profiles ON replies.user_id = profiles.user_id
    WHERE replies.post_id = ?
    ORDER BY reply_created_at DESC
    ';

    $result = $db->prepareAndFetchAll($query, 'i', [$postId]) ?? null;

    if (count($result) === 0) return null;
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


  private function resultToReply(array $data): array
  {
    $upload_file_path = is_null($data['file_path']) ? null : FileHelper::getUploadFilePath($data['file_path'], $data['file_type']);

    return [
      'reply' =>
      new Reply(
        content: $data['content'],
        user_id: $data['user_id'],
        post_id: $data['post_id'],
        id: $data['reply_id'],
        parent_reply_id: $data['parent_reply_id'],
        status: $data['status'],
        deleted_at: $data['deleted_at'],
        dataTimeStamp: new DataTimeStamp($data['reply_created_at'], $data['reply_updated_at']),
        file_path: $upload_file_path,
        file_type: $data['file_type']
      ),
      'profile' =>
      new Profile(
        user_id: $data['profile_user_id'],
        id: $data['profile_id'],
        username: $data['username'],
        age: $data['age'],
        address: $data['address'],
        hobby: $data['hobby'],
        self_introduction: $data['self_introduction'],
        profile_image_path: $data['profile_image_path'],
        timeStamp: new DataTimeStamp($data['profile_created_at'], $data['profile_updated_at'])
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
