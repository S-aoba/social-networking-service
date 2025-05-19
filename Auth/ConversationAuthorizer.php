<?php

namespace Auth;

use Models\Conversation;

class ConversationAuthorizer 
{
  public function isJoin(int $userId, Conversation $conversation): bool {
    return $conversation->getUser1Id() === $userId || $conversation->getUser2Id() === $userId;
  }
}