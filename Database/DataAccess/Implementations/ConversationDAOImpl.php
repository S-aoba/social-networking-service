<?php

namespace Database\DataAccess\Implementations;

use Database\DataAccess\Interfaces\ConversationDAO;
use Database\DatabaseManager;
use Models\Conversation;
use Models\DataTimeStamp;
use Helpers\FileHelper;

class ConversationDAOImpl implements ConversationDAO
{
  public function createConversation(Conversation $conversation): bool
  {

    return true;
  }

  public function deleteConversation(int $conversation_id): bool
  {
    return true;
  }

  public function getAllConversations(int $user_id): ?array
  {
    $db = DatabaseManager::getMysqliConnection();

    $query =
      'SELECT c.*, p1.profile_image_path AS p1_profile_image_path, p1.username AS p1_username, p2.profile_image_path AS p2_profile_image_path, p2.username AS p2_username
      FROM conversations c
      INNER JOIN profiles p1 ON c.participant1_id = p1.user_id
      INNER JOIN profiles p2 ON c.participant2_id = p2.user_id
      WHERE c.participant1_id = ? OR c.participant2_id = ?
    ';

    $result = $db->prepareAndFetchAll($query, 'ii', [$user_id, $user_id]);
    
    if ($result === null) return null;

    return $this->resultsConversation($result);
  }

  private function resultConversation(array $result): array
  {
    $p1_profile_image_path = FileHelper::getProfileImagePath($result['p1_profile_image_path']);
    $p2_profile_image_path = FileHelper::getProfileImagePath($result['p2_profile_image_path']);

    return [
      'conversation' => new Conversation(
        participant1_id: $result['participant1_id'],
        participant2_id: $result['participant2_id'],
        conversation_id: $result['conversation_id'],
        dataTimeStamp: new DataTimeStamp($result['created_at'], $result['updated_at'])
      ),
      'p1_profile_image_path' => $p1_profile_image_path,
      'p2_profile_image_path' => $p2_profile_image_path,
      'p1_username' => $result['p1_username'],
      'p2_username' => $result['p2_username']
    ];
  }

  private function resultsConversation(array $results): array
  {
    $data_list = [];
    foreach ($results as $result) {
      $data_list[] = $this->resultConversation($result);
    }
    return $data_list;
  }
}
