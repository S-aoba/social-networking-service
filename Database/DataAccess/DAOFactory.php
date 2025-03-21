<?php

namespace Database\DataAccess;

use Database\DataAccess\Implementations\ComputerPartDAOImpl;
use Database\DataAccess\Implementations\ComputerPartDAOMemcachedImpl;
use Database\DataAccess\Implementations\PostDAOImpl;
use Database\DataAccess\Implementations\ProfileDAOImpl;
use Database\DataAccess\Implementations\UserDAOImpl;
use Database\DataAccess\Interfaces\ComputerPartDAO;
use Database\DataAccess\Interfaces\PostDAO;
use Database\DataAccess\Interfaces\ProfileDAO;
use Database\DataAccess\Interfaces\UserDAO;
use Helpers\Settings;


class DAOFactory
{
    public static function getComputerPartDAO(): ComputerPartDAO{
        $driver = Settings::env('DATABASE_DRIVER');

        return match ($driver) {
            'memcached' => new ComputerPartDAOMemcachedImpl(),
            default => new ComputerPartDAOImpl(),
        };
    }

    public static function getUserDAO(): UserDAO{
        $driver = Settings::env('DATABASE_DRIVER');

        return match ($driver) {
            'memcached' => new UserDAOImpl(),
            default => new UserDAOImpl(),
        };
    }

    public static function getProfileDAO(): ProfileDAO{
        $driver = Settings::env('DATABASE_DRIVER');

        return match ($driver) {
            'memcached' => new ProfileDAOImpl(),
            default => new ProfileDAOImpl(),
        };
    }

    public static function getPostDAO(): PostDAO{
        $driver = Settings::env('DATABASE_DRIVER');

        return match ($driver) {
            'memcached' => new PostDAOImpl(),
            default => new PostDAOImpl(),
        };
    }
}