<?php

namespace Database\DataAccess;

use Database\DataAccess\Implementations\ComputerPartDAOImpl;
use Database\DataAccess\Implementations\PostDAOImpl;
use Database\DataAccess\Implementations\UserDAOImpl;
use Helpers\Settings;

class DAOFactory
{
  public static function getComputerPartDAO(): ComputerPartDAOImpl
  {
    $driver = Settings::env('DATABASE_DRIVER');

    return match ($driver) {
        // 'memcached' => new ComputerPartDAOMemcachedImpl(),
      default => new ComputerPartDAOImpl(),
    };
  }

  public static function getUserDAO(): UserDAOImpl
  {
    $driver = Settings::env('DATABASE_DRIVER');

    return match ($driver) {
        // 'memcached' => new ComputerPartDAOMemcachedImpl(),
      default => new UserDAOImpl(),
    };
  }

  public static function getPostDAO(): PostDAOImpl
  {
    $driver = Settings::env('DATABASE_DRIVER');

    return match ($driver) {
        // 'memcached' => new ComputerPartDAOMemcachedImpl(),
      default => new PostDAOImpl(),
    };
  }
}
