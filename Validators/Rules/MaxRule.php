<?php

namespace Validators\Rules;

class MaxRule
{
    public static function validate(string $field, $value, int $max): array
    {
        if (is_string($value)) {
            if (mb_strlen($value) > $max) {
                throw new \InvalidArgumentException("{$field} must be at most {$max} characters.");
            }
            return [$field => $value];
        }

        if (is_int($value) || ctype_digit((string)$value)) {
            if ((int)$value > $max) {
                throw new \InvalidArgumentException("{$field} must be at most {$max}.");
            }
            return [$field => (int)$value];
        }

        throw new \InvalidArgumentException("{$field} must be a string or integer for max validation.");
    }
}
