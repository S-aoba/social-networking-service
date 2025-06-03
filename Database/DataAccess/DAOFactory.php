<?php

namespace Database\DataAccess;

use Database\DataAccess\Implementations\ConversationDAOImpl;
use Database\DataAccess\Implementations\DirectMessageDAOImpl;
use Database\DataAccess\Implementations\FollowDAOImpl;
use Database\DataAccess\Implementations\LikeDAOImpl;
use Database\DataAccess\Implementations\NotificationDAOImpl;
use Database\DataAccess\Implementations\PostDAOImpl;
use Database\DataAccess\Implementations\ProfileDAOImpl;
use Database\DataAccess\Implementations\UserDAOImpl;
use Database\DataAccess\Interfaces\ConversationDAO;
use Database\DataAccess\Interfaces\DirectMessageDAO;
use Database\DataAccess\Interfaces\FollowDAO;
use Database\DataAccess\Interfaces\LikeDAO;
use Database\DataAccess\Interfaces\NotificationDAO;
use Database\DataAccess\Interfaces\PostDAO;
use Database\DataAccess\Interfaces\ProfileDAO;
use Database\DataAccess\Interfaces\UserDAO;
use Helpers\Settings;

class DAOFactory
{
    public static function getUserDAO(): UserDAO
    {
        $driver = Settings::env('DATABASE_DRIVER');

        return match ($driver) {
            default => new UserDAOImpl(),
        };
    }

    public static function getProfileDAO(): ProfileDAO
    {
        $driver = Settings::env('DATABASE_DRIVER');

        return match ($driver) {
            default => new ProfileDAOImpl(),
        };
    }

    public static function getPostDAO(): PostDAO
    {
        $driver = Settings::env('DATABASE_DRIVER');

        return match ($driver) {
            default => new PostDAOImpl(),
        };
    }

    public static function getFollowDAO(): FollowDAO
    {
        $driver = Settings::env('DATABASE_DRIVER');

        return match ($driver) {
            default => new FollowDAOImpl(),
        };
    }

    public static function getLikeDAO(): LikeDAO
    {
        $driver = Settings::env('DATABASE_DRIVER');

        return match ($driver) {
            default => new LikeDAOImpl(),
        };
    }

    public static function getConversationDAO(): ConversationDAO
    {
        $driver = Settings::env('DATABASE_DRIVER');

        return match ($driver) {
            default => new ConversationDAOImpl(),
        };
    }

    public static function getDirectMessage(): DirectMessageDAO
    {
        $driver = Settings::env('DATABASE_DRIVER');

        return match ($driver) {
            default => new DirectMessageDAOImpl(),
        };
    }

    public static function getNotification(): NotificationDAO
    {
        $driver = Settings::env('DATABASE_DRIVER');

        return match ($driver) {
            default => new NotificationDAOImpl(),
        };
    }
}
