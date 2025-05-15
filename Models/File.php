<?php

namespace Models;

use Models\Interfaces\Model;
use Models\Traits\GenericModel;

class File implements Model {
  use GenericModel;

  public function __construct(
    private string $name,
    private string $fullPath,
    private string $type,
    private string $tmpName,
    private int $error,
    private int $size
  )
  {}

  public function getName(): ?string {
    return $this->name === '' ? null : $this->name;
  }

  public function getFullPath(): ?string {
    return $this->fullPath === '' ? null : $this->fullPath;
  }

  public function getType(): ?string {
    return $this->type === '' ? null : $this->type;
  }

  public function getTypeWithoutPrefix(): ?string {
    if ($this->getType() !== null && str_starts_with($this->getType(), 'image/')) {
        return substr($this->getType(), strlen('image/'));
    }
    return null;
  }

  public function getTmpName(): ?string {
    return $this->tmpName === '' ? null : $this->tmpName;
  }

  public function getError(): int {
    return $this->error;
  }

  public function getSize(): int {
    return $this->size;
  }

  public function isValid(): bool {
    return $this->getError() === UPLOAD_ERR_OK &&
           !empty($this->getTmpName()) &&
           is_uploaded_file($this->getTmpName()) &&
           $this->getSize() > 0;
  }

  public static function fromArray(array $file): self {
    return new self(
        name: $file['name'] ?? '',
        fullPath: $file['full_path'] ?? '',
        type: $file['type'] ?? '',
        tmpName: $file['tmp_name'] ?? '',
        error: $file['error'] ?? UPLOAD_ERR_NO_FILE,
        size: $file['size'] ?? 0
    );
  }
}