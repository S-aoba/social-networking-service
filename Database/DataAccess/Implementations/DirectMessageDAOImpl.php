<?php

namespace Database\DataAccess\Implementations;

use Database\DataAccess\Interfaces\DirectMessageDAO;
use Database\DatabaseManager;
use Models\DirectMessge;

class DirectMessageDAOImpl implements DirectMessageDAO {
  public function create(DirectMessge $directMessage): bool
  {
    return false;
  }
  
  public function findByConversationId(int $conversationId): ?array
  {
    return null;
  }
}