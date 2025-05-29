<?php

namespace Validators\Rules;

class StringRule
{
    public static function validate(string $field, $value): array
    {
        return is_string($value) ? [$field => $value] : throw new \InvalidArgumentException("{$field} must be a string.");
    }
}
