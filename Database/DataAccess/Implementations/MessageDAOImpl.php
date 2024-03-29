<?php

namespace Database\DataAccess\Implementations;

use Database\DataAccess\Interfaces\MessageDAO;
use Database\DatabaseManager;
use Models\Message;
use Models\DataTimeStamp;
use Helpers\Settings;
use Helpers\EncryptionHelper;

class MessageDAOImpl implements MessageDAO
{
  public function createMessage(Message $message): bool
  {

    $db = DatabaseManager::getMysqliConnection();

    $query = 'INSERT INTO messages (sender_id, receiver_id, conversation_id, message_body) VALUES (?, ?, ?, ?)';

    $result = $db->prepareAndExecute(
      $query,
      'iiis',
      [
        $message->getSenderId(),
        $message->getReceiverId(),
        $message->getConversationId(),
        $message->getMessageBody()
      ]
    );

    if ($result) return true;

    return false;
  }

  public function getAllMessageById(int $conversation_id): array
  {

    $db = DatabaseManager::getMysqliConnection();


    $query =
      ' SELECT *
      FROM messages
      WHERE conversation_id = ?
    ';

    $result = $db->prepareAndFetchAll($query, 'i', [$conversation_id]);


    return $this->resultsMessage($result);
  }

  public function getMessageFirst(int $conversation_id): array
  {
    $db = DatabaseManager::getMysqliConnection();

    $query =
      'SELECT *
      FROM messages
      WHERE conversation_id = ?
      ORDER BY message_id DESC
      LIMIT 1;
    ';

    $result = $db->prepareAndFetchAll($query, 'i', [$conversation_id]);
    if (count($result) === 0) return [];
    return $this->resultsMessage($result);
  }

  private function resultToMessage(array $result): Message
  {

    $sent_at = date("Y-m-d", strtotime($result['sent_at']));
    $read_at = date("Y-m-d", strtotime($result['read_at']));

    $encryptionKey = Settings::env('ENCRYPTION_KEY');

    $encryptionHelper = new EncryptionHelper($encryptionKey);

    $encryptedMessageBody = $encryptionHelper->decrypt($result['message_body']);

    return new Message(
      sender_id: $result['sender_id'],
      receiver_id: $result['receiver_id'],
      conversation_id: $result['conversation_id'],
      message_body: $encryptedMessageBody,
      message_id: $result['message_id'],
      dataTimeStamp: new DataTimeStamp($sent_at, $read_at)
    );
  }

  private function resultsMessage(array $results): array
  {
    $data_list = [];
    foreach ($results as $result) {
      $data_list[] = $this->resultToMessage($result);
    }
    return $data_list;
  }
}
