<?php

namespace Models;

use Exception;

class DataTimeStamp
{
  private string $createdAt;
  private string $updatedAt;

  public function __construct(string $createdAt, string $updatedAt)
  {
    $this->createdAt = $createdAt;
    $this->updatedAt = $updatedAt;
  }

  public function getCreatedAt(): string
  {
    return $this->createdAt;
  }

  public function getUpdatedAt(): string
  {
    return $this->updatedAt;
  }

  public function setCreatedAt(string $createdAt): void
  {
    $this->createdAt = $createdAt;
  }

  public function setUpdatedAt(string $updatedAt): void
  {
    $this->updatedAt = $updatedAt;
  }

  public function CalculatePostAge(): string
  {
    date_default_timezone_set('Asia/Tokyo');
    try {
      if ($this->createdAt === null) {
        throw new Exception('日付がnullになっています。');
      }

      $created_at = strtotime($this->createdAt);
      $current_time = time();

      $time_diff = abs($current_time - $created_at); // 絶対値を取ることで常に正の値になる

      $days_diff = floor($time_diff / (60 * 60 * 24)); // 日数の差を計算
      $hours_diff = floor(($time_diff % (60 * 60 * 24)) / (60 * 60)); // 残りの時間から時間の差を計算
      $minutes_diff = floor(($time_diff % (60 * 60)) / 60); // 残りの分から分の差を計算

      if ($days_diff > 0) {
        return $days_diff . '日前';
      } elseif ($hours_diff > 0) {
        return $hours_diff . '時間前';
      } elseif ($minutes_diff > 0) {
        return $minutes_diff . '分前';
      } else {
        return 'たった今';
      }
    } catch (Exception $e) {
      error_log($e->getMessage());
      return "エラーが発生しました";
    }
  }
}
