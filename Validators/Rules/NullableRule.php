<?php

namespace Validators\Rules;

class NullableRule
{
  public static function validate(string $field, $value): array
  {
    return [$field => $value ?? null];
  }
}