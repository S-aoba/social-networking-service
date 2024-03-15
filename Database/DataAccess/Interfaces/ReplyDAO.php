<?php

namespace Database\DataAccess\Interfaces;

use Models\Reply;

interface ReplyDAO
{
  public function createReply(Reply $reply): bool;
  public function deleteReply(int $id): bool;
  public function getReplyByPostId(int $postId): ?array;
  public function getReplyCountForPost(int $postId): int;
}
