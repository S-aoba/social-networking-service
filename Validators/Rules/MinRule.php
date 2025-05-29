<?php

namespace Validators\Rules;

class MinRule
{
    public static function validate(string $field, $value, int $min): array
    {
        if (is_string($value)) {
            if (mb_strlen($value) < $min) {
                throw new \InvalidArgumentException("{$field} must be at least {$min} characters.");
            }
            return [$field => $value];
        }

        if (is_int($value) || ctype_digit((string)$value)) {
            if ((int)$value < $min) {
                throw new \InvalidArgumentException("{$field} must be at least {$min}.");
            }
            return [$field => (int)$value];
        }

        throw new \InvalidArgumentException("{$field} must be a string or integer for min validation.");
    }
}
