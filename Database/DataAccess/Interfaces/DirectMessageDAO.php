<?php

namespace Database\DataAccess\Interfaces;

use Models\DirectMessge;

interface DirectMessageDAO
{
    public function create(DirectMessge $directMessage): bool;
    public function getAllByConversationId(int $conversationId): ?array;
}
