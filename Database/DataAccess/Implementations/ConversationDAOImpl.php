<?php

namespace Database\DataAccess\Implementations;

use Database\DataAccess\DAOFactory;
use Database\DataAccess\Interfaces\ConversationDAO;
use Database\DatabaseManager;
use Models\Conversation;
use Models\DataTimeStamp;
use Helpers\FileHelper;
use Models\Profile;

class ConversationDAOImpl implements ConversationDAO
{
  public function createConversation(Conversation $conversation): bool
  {

    $db = DatabaseManager::getMysqliConnection();

    $query = 'INSERT INTO conversations (participant1_id, participant2_id) VALUES (?, ?)';

    $result = $db->prepareAndExecute(
      $query,
      'ii',
      [
        $conversation->getParticipate1Id(),
        $conversation->getParticipate2Id()
      ]
    );

    $conversationId = $db->insert_id;
    $conversation->setConversationId($conversationId);

    if ($result) return true;
    return false;
  }

  public function deleteConversation(int $conversation_id): bool
  {

    $db = DatabaseManager::getMysqliConnection();


    $query = 'DELETE FROM conversations WHERE conversation_id = ?';


    $result = $db->prepareAndExecute(
      $query,
      'i',
      [$conversation_id]
    );


    if ($result === false) return false;

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


  public function getConversationById(int $conversation_id): ?Conversation
  {
    $db = DatabaseManager::getMysqliConnection();


    $query =
      ' SELECT *
      From conversations
      WhERE conversation_id = ?
    ';

    $result = $db->prepareAndFetchAll($query, 'i', [$conversation_id]);

    if(count($result) === 0) return null;
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
    $receiver_profile_image_path = match (true) {
      is_null($result['p1_profile_image_path']) && is_null($result['p2_profile_image_path']) => null,
      $result['p1_user_id'] === $_SESSION['user_id'] && is_null($result['p2_profile_image_path']) => null,
      $result['p2_user_id'] === $_SESSION['user_id'] && is_null($result['p1_profile_image_path']) => null,
      $result['p1_user_id'] === $_SESSION['user_id'] => $result['p2_profile_image_path'],
      $result['p2_user_id'] === $_SESSION['user_id'] => $result['p1_profile_image_path']
    };

    $receiver_username = $result['p1_user_id'] === $_SESSION['user_id'] ? $result['p2_username'] : $result['p1_username'];
    $receiver_user_id = $result['p1_user_id'] === $_SESSION['user_id'] ? $result['p2_user_id'] : $result['p1_user_id'];

    $created_at = date("Y-m-d", strtotime($result['created_at']));
    $updated_at = date("Y-m-d", strtotime($result['updated_at']));

    $messageDAO = DAOFactory::getMessage();

    $latest_message = $messageDAO->getMessageFirst($result['conversation_id']);


    return [
      'conversation' => new Conversation(
        participant1_id: $result['participant1_id'],
        participant2_id: $result['participant2_id'],
        conversation_id: $result['conversation_id'],
        dataTimeStamp: new DataTimeStamp($created_at, $updated_at)
      ),
      'receiver' => new Profile(
        user_id: $receiver_user_id,
        username: $receiver_username,
        profile_image_path: $receiver_profile_image_path
      ),
      'latest_message' => $latest_message
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
