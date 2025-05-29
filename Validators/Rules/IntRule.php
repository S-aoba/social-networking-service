<?php

namespace Validators\Rules;

class IntRule
{
    public static function validate(string $field, $value): array
    {
        return filter_var($value, FILTER_VALIDATE_INT) !== false ?
                [$field => (int)$value]
                :
                throw new \InvalidArgumentException("{$field} must be an integer.");
    }
}
