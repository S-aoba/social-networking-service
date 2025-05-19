<?php

namespace Auth;

use Database\DataAccess\Interfaces\ProfileDAO;
use Models\Conversation;

class ConversationAuthorizer 
{
  public function isJoin(int $userId, Conversation $conversation): bool {
    return $conversation->getUser1Id() === $userId || $conversation->getUser2Id() === $userId;
  }

  public function isExistsPartnerUser(int $userId, Conversation $conversation, ProfileDAO $profileDAO): bool {
    $partnerId = $this->getPartnerId($userId, $conversation);

    $partnerProfile =  $profileDAO->getByUserId($partnerId);

    return $partnerProfile !== null;
  }

  private function getPartnerId(int $userId, Conversation $conversation): int {
    return $conversation->getUser1Id() === $userId ? $conversation->getUser2Id() : $conversation->getUser1Id();
  }
}