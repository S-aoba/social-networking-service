<?php

namespace Database\DataAccess\Interfaces;

use Models\Conversation;

interface ConversationDAO
{
  public function createConversation(Conversation $conversation): bool;
  public function deleteConversation(int $conversation_id): bool;
  public function getAllConversations(int $user_id): ?array;
}
