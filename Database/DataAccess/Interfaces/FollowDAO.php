<?php

namespace Database\DataAccess\Interfaces;

use Models\Follow;

interface FollowDAO
{
  public function addFollow(Follow $follow): bool;
  public function removeFollow(Follow $follow): bool;
}
