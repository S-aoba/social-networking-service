<?php

namespace Models;

use Models\Interfaces\Model;
use Models\Traits\GenericModel;

class Conversation implements Model
{
  use GenericModel;

  public function __construct(
    private int $participant1_id,
    private int $participant2_id,
    private ?int $conversation_id = null,
    private ?DataTimeStamp $dataTimeStamp = null
  ) {
  }

  public function getParticipate1Id(): int
  {
    return $this->participant1_id;
  }

  public function setParticipate1Id(int $participant1_id): void
  {
    $this->participant1_id = $participant1_id;
  }

  public function getParticipate2Id(): int
  {
    return $this->participant2_id;
  }

  public function setParticipate2Id(int $participant2_id): void
  {
    $this->participant2_id = $participant2_id;
  }

  public function getConversationId(): ?int
  {
    return $this->conversation_id;
  }

  public function setConversationId(?int $conversation_id): void
  {
    $this->conversation_id = $conversation_id;
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
