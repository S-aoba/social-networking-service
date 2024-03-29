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
    private ?DataTimeStamp $timeStamp = null,
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

  public function getTimeStamp(): ?DataTimeStamp
  {
    return $this->timeStamp;
  }

  public function setTimeStamp(DataTimeStamp $timeStamp): void
  {
    $this->timeStamp = $timeStamp;
  }

  public function getTable(): string
  {
    return $this->table;
  }
}
