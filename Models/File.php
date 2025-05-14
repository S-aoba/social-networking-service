<?php

namespace Models;

use Models\Interfaces\Model;
use Models\Traits\GenericModel;

class File implements Model {
  use GenericModel;

  public function __construct(
    private array $file
  )
  {}

  public function getName(): ?string {
    return $this->file['name'] === '' ? null : $this->file['name'];
  }

  public function getFullPath(): ?string {
    return $this->file['full_path'] === '' ? null : $this->file['full_path'];
  }

  public function getType(): ?string {
    return $this->file['type'] === '' ? null : $this->file['type'];
  }

  public function getTmpName(): ?string {
    return $this->file['tmp_name'] === '' ? null : $this->file['tmp_name'];
  }

  public function getError(): int {
    return $this->file['error'];
  }

  public function getSize(): int {
    return $this->file['size'];
  }
}