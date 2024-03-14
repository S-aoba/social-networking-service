<?php

namespace Database\DataAccess\Interfaces;

use Models\Reply;

interface ReplyDAO
{
  public function createReply(Reply $reply): bool;
  public function updateReply(Reply $reply): bool;
  public function deleteReply(int $id): bool;
  public function getReplyById(int $replyId): ?Reply;
  public function getReplyCountForPost(int $postId): int;
}
