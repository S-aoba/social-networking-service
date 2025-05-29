<?php

namespace Auth;

class Authorizer
{
    public static function isOwnedByUser(int $resourceOwnerId, int $authUserId): bool
    {
        return $resourceOwnerId === $authUserId;
    }
}
