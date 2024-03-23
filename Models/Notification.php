<?php

namespace Models;

use Models\Interfaces\Model;
use Models\Traits\GenericModel;
use Exception;

class Notification implements Model
{
  use GenericModel;

  protected $table = 'notifications';

  public function __construct(
    private int $sender_id,
    private int $receiver_id,
    private string $type,
    private ?string $content = null,
    private bool $read_status = false,
    private ?int $notification_id = null,
    private ?string $created_at = null
  ) {
  }

  public function getSenderId(): int
  {
    return $this->sender_id;
  }

  public function setSenderId(int $sender_id): void
  {
    $this->sender_id = $sender_id;
  }

  public function getReceiverId(): int
  {
    return $this->receiver_id;
  }

  public function setReceiverId(int $receiver_id): void
  {
    $this->receiver_id = $receiver_id;
  }

  public function getType(): string
  {
    return $this->type;
  }

  public function setType(string $type): void
  {
    $this->type = $type;
  }

  public function getContent(): ?string
  {
    return $this->content;
  }

  public function setContent(?string $content): void
  {
    $this->content = $content;
  }

  public function getReadStatus(): bool
  {
    return $this->read_status;
  }

  public function setReadStatus(bool $read_status): void
  {
    $this->read_status = $read_status;
  }

  public function getNotificationId(): ?int
  {
    return $this->notification_id;
  }

  public function setNotificationId(?int $notification_id): void
  {
    $this->notification_id = $notification_id;
  }

  public function getCreatedAt(): ?string
  {
    return $this->created_at;
  }

  public function setCreatedAt(?string $created_at): void
  {
    $this->created_at = $created_at;
  }

  public function getTable(): string
  {
    return $this->table;
  }

  public function diff(): string
  {
    date_default_timezone_set('Asia/Tokyo');
    try {
      if ($this->created_at === null) {
        throw new Exception('日付がnullになっています。');
      }

      $created_at = strtotime($this->created_at);
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
