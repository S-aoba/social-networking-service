<?php

namespace Services\Conversation;

use Database\DataAccess\Interfaces\ProfileDAO;
use Models\Conversation;
use Models\Profile;

class ConversationParnerResolver
{
    public function __construct(
        private ProfileDAO $profileDAO
    ) {
    }

    public function resolverPartnerProfile(int $authUserId, Conversation $conversation): ?Profile
    {
        $partnerId = $this->resolverPartnerId($authUserId, $conversation);

        return $this->profileDAO->getByUserId($partnerId);
    }

    private function resolverPartnerId(int $authUserId, Conversation $conversation): int
    {
        return $authUserId === $conversation->getUser2Id()
                    ? $conversation->getUser1Id()
                    : $conversation->getUser2Id();
    }
}
