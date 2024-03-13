<?php

namespace Database\DataAccess;

use Database\DataAccess\Implementations\ComputerPartDAOImpl;
use Database\DataAccess\Implementations\PostDAOImpl;
use Database\DataAccess\Implementations\UserDAOImpl;
use Database\DataAccess\Implementations\ProfileDAOImpl;
use Database\DataAccess\Implementations\FollowDAOImpl;
use Database\DataAccess\Implementations\PostLikeDAOImpl;

use Helpers\Settings;

class DAOFactory
{
  public static function getComputerPartDAO(): ComputerPartDAOImpl
  {
    $driver = Settings::env('DATABASE_DRIVER');

    return match ($driver) {
      default => new ComputerPartDAOImpl(),
    };
  }

  public static function getUserDAO(): UserDAOImpl
  {
    $driver = Settings::env('DATABASE_DRIVER');

    return match ($driver) {
      default => new UserDAOImpl(),
    };
  }

  public static function getPostDAO(): PostDAOImpl
  {
    $driver = Settings::env('DATABASE_DRIVER');

    return match ($driver) {
      default => new PostDAOImpl(),
    };
  }

  public static function getProfileDAO(): ProfileDAOImpl
  {
    $driver = Settings::env('DATABASE_DRIVER');

    return match ($driver) {
      default => new ProfileDAOImpl(),
    };
  }

  public static function getFollowDAO(): FollowDAOImpl
  {
    $driver = Settings::env('DATABASE_DRIVER');

    return match ($driver) {
      default => new FollowDAOImpl(),
    };
  }

  public static function getPostLikeDAO(): PostLikeDAOImpl
  {
    $driver = Settings::env('DATABASE_DRIVER');

    return match ($driver) {
      default => new PostLikeDAOImpl(),
    };
  }
}
