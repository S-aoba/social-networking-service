<?php

namespace Database\DataAccess\Interfaces;

use Models\Message;

interface MessageDAO
{
  public function createMessage(Message $message): bool;
  public function getMessageFirst(int $conversation_id): array;
  public function getAllMessageById(int $conversation_id): array;
}
