<?php

namespace Models;

use Models\Interfaces\Model;
use Models\Traits\GenericModel;

class Message implements Model
{
  use GenericModel;

  public function __construct(
    private int $sender_id,
    private int $receiver_id,
    private int $conversation_id,
    private string $message_body,
    private ?int $message_id = null,
    private ?DataTimeStamp $dataTimeStamp = null
  ) {
  }

  public function getSenderId(): int
  {
    return $this->sender_id;
  }

  public function setSenderId(int $sender_id): void
  {
    $this->sender_id = $sender_id;
  }

  public function getReceiverId(): int
  {
    return $this->receiver_id;
  }

  public function setReceiverId(int $receiver_id): void
  {
    $this->receiver_id = $receiver_id;
  }

  public function getConversationId(): int
  {
    return $this->conversation_id;
  }

  public function setConversationId(int $conversation_id): void
  {
    $this->conversation_id = $conversation_id;
  }

  public function getMessageBody(): string
  {
    return $this->message_body;
  }

  public function setMessageBody(string $message_body): void
  {
    $this->message_body = $message_body;
  }

  public function getMessageId(): ?int
  {
    return $this->message_id;
  }

  public function setMessageId(?int $message_id): void
  {
    $this->message_id = $message_id;
  }

  public function getDataTimeStamp(): ?DataTimeStamp
  {
    return $this->dataTimeStamp;
  }

  public function setDataTimeStamp(?DataTimeStamp $dataTimeStamp): void
  {
    $this->dataTimeStamp = $dataTimeStamp;
  }
}
