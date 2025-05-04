<?php

namespace Database\DataAccess\Implementations;

use Database\DataAccess\Interfaces\ConversationDAO;
use Models\Conversation;

class ConversationDAOImpl implements ConversationDAO {
  public function create(Conversation $conversation): bool
  {
    return false;    
  }

  public function findAllByUserId(int $userId): ?array
  {
    return null;
  }

  public function delete(int $id): bool
  {
    return false;
  }
}