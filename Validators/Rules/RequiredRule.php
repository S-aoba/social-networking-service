<?php

namespace Validators\Rules;

class RequiredRule
{
    public static function validate(string $field, $value): array
    {
        return isset($value) && $value !== '' ?
                [$field => $value]
                :
                throw new \InvalidArgumentException("{$field} is required.");
    }
}
