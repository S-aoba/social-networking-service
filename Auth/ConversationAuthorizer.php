<?php

namespace Auth;

use Database\DataAccess\Interfaces\FollowDAO;
use Database\DataAccess\Interfaces\ProfileDAO;
use Models\Conversation;

class ConversationAuthorizer extends Authorizer
{
    public function isMutualFollow(FollowDAO $followDAO, int $userId, int $partnerId): bool
    {
        return $followDAO->isMutualFollow($userId, $partnerId);
    }

    public function isSameId(int $resourceUserId, int $authUserId): bool
    {
        return $resourceUserId === $authUserId;
    }

    public function isJoin(int $userId, Conversation $conversation): bool
    {
        return $conversation->getUser1Id() === $userId || $conversation->getUser2Id() === $userId;
    }

    public function isExistsPartnerUser(int $userId, Conversation $conversation, ProfileDAO $profileDAO): bool
    {
        $partnerId = $this->getPartnerId($userId, $conversation);

        $partnerProfile =  $profileDAO->getByUserId($partnerId);

        return $partnerProfile !== null;
    }

    private function getPartnerId(int $userId, Conversation $conversation): int
    {
        return $conversation->getUser1Id() === $userId ? $conversation->getUser2Id() : $conversation->getUser1Id();
    }
}
