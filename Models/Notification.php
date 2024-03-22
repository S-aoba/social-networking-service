<?php

namespace Models;

use Models\Interfaces\Model;
use Models\Traits\GenericModel;

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
    // 現在の時間を取得します。
    $current_time = time();

    // 差を計算します。
    $time_difference = $current_time - strtotime($this->created_at);

    // 分単位の差を計算します。
    $minutes_difference = round($time_difference / 60);

    // 結果を表示します。
    if ($minutes_difference < 60) {
      return $minutes_difference . "分前";
    } elseif ($minutes_difference < 1440) { // 24時間未満の場合
      $hours_difference = round($minutes_difference / 60);
      return $hours_difference . "時間前";
    } else {
      $days_difference = round($minutes_difference / 1440);
      return $days_difference . "日前";
    }
  }
}
