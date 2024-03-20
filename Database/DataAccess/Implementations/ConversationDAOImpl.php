<?php

namespace Database\DataAccess\Implementations;

use Database\DataAccess\DAOFactory;
use Database\DataAccess\Interfaces\ConversationDAO;
use Database\DatabaseManager;
use Models\Conversation;
use Models\DataTimeStamp;
use Helpers\FileHelper;

class ConversationDAOImpl implements ConversationDAO
{
  public function createConversation(Conversation $conversation): bool
  {

    $db = DatabaseManager::getMysqliConnection();

    $query = 'INSERT INTO conversations (conversation_id, participant1_id, participant2_id) VALUES (?, ?, ?)';

    $result = $db->prepareAndExecute(
      $query,
      'iii',
      [
        $conversation->getConversationId(),
        $conversation->getParticipate1Id(),
        $conversation->getParticipate2Id()
      ]
    );

    if ($result) return true;
    return false;
  }

  public function deleteConversation(int $conversation_id): bool
  {
    return true;
  }

  public function getAllConversations(int $user_id): ?array
  {
    $db = DatabaseManager::getMysqliConnection();

    $query =
      'SELECT conversations.*,
      p1.username AS p1_username, p2.username AS p2_username, p1.user_id AS p1_user_id, p2.user_id AS p2_user_id,
      p1.profile_image_path AS p1_profile_image_path, p2.profile_image_path AS p2_profile_image_path
      FROM conversations
      INNER JOIN profiles p1 ON conversations.participant1_id = p1.user_id
      INNER JOIN profiles p2 ON conversations.participant2_id = p2.user_id
      WHERE conversations.participant1_id = ? OR conversations.participant2_id = ?
    ';

    $result = $db->prepareAndFetchAll($query, 'ii', [$user_id, $user_id]);

    if ($result === null) return null;

    return $this->resultsConversation($result);
  }


  public function getConversationById(int $conversation_id): Conversation
  {
    $db = DatabaseManager::getMysqliConnection();


    $query =
      ' SELECT *
      From conversations
      WhERE conversation_id = ?
    ';

    $result = $db->prepareAndFetchAll($query, 'i', [$conversation_id]);


    return $this->rawDataToConversation($result[0]);
  }

  private function rawDataToConversation(array $result): Conversation
  {

    $created_at = date("Y-m-d", strtotime($result['created_at']));
    $updated_at = date("Y-m-d", strtotime($result['updated_at']));

    return new Conversation(
      participant1_id: $result['participant1_id'],
      participant2_id: $result['participant2_id'],
      conversation_id: $result['conversation_id'],
      dataTimeStamp: new DataTimeStamp($created_at, $updated_at)
    );
  }


  private function resultConversation(array $result): array
  {
    // メッセージ相手のprofile_image_path, username, IDを取得する
    $profile_image_path = $result['p1_user_id'] === $_SESSION['user_id'] ? FileHelper::getProfileImagePath($result['p2_profile_image_path']) : FileHelper::getProfileImagePath($result['p1_profile_image_path']);

    $username = $result['p1_user_id'] === $_SESSION['user_id'] ? $result['p2_username'] : $result['p1_username'];
    $user_id = $result['p1_user_id'] === $_SESSION['user_id'] ? $result['p2_user_id'] : $result['p1_user_id'];

    $created_at = date("Y-m-d", strtotime($result['created_at']));
    $updated_at = date("Y-m-d", strtotime($result['updated_at']));

    $messageDAO = DAOFactory::getMessage();

    $message = $messageDAO->getMessageFirst($result['conversation_id']);


    return [
      'conversation' => new Conversation(
        participant1_id: $result['participant1_id'],
        participant2_id: $result['participant2_id'],
        conversation_id: $result['conversation_id'],
        dataTimeStamp: new DataTimeStamp($created_at, $updated_at)
      ),
      'other_user_profile_image_path' => $profile_image_path,
      'other_user_name' => $username,
      'other_user_id' => $user_id,
      'message' => $message
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
