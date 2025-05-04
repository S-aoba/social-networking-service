<?php

namespace Database\DataAccess\Interfaces;

use Models\Conversation;

interface ConversationDAO {
  public function create(Conversation $conversation): bool;
  public function findAllByUserId(int $userId): ?array;
  public function existsByUserPair(Conversation $conversation): bool;
  public function delete(int $id): bool;
}