<?php

namespace Validators;

use Validators\Rules\ExistsRule;
use Validators\Rules\IntRule;
use Validators\Rules\MaxRule;
use Validators\Rules\MinRule;
use Validators\Rules\NullableRule;
use Validators\Rules\RequiredRule;
use Validators\Rules\StringRule;

class Validator
{
    public function __construct(
        // array<$field, string[rules]>
        private array $rules
    ) {
    }

    public function validate(array $data): array
    {
        $validatedData = [];

        foreach ($this->rules as $field => $rules) {
            $rules = explode('|', $rules);

            foreach ($rules as $rule) {
                $validatedValue = $this->applyRule($field, $data[$field], $rule);

                $validatedData[$field] = $validatedValue[$field];
            }
        }
        return $validatedData;
    }


    private function applyRule(string $field, $value, string $rule): array
    {
        if ($rule === 'required') {
            return RequiredRule::validate($field, $value);
        } elseif ($rule === 'string') {
            return StringRule::validate($field, $value);
        } elseif ($rule === 'int') {
            return IntRule::validate($field, $value);
        } elseif ($rule === 'nullable') {
            return NullableRule::validate($field, $value);
        } elseif (str_starts_with($rule, 'min:')) {
            $min = (int)substr($rule, 4);
            return MinRule::validate($field, $value, $min);
        } elseif (str_starts_with($rule, 'max:')) {
            $max = (int)substr($rule, 4);
            return MaxRule::validate($field, $value, $max);
        } elseif (str_starts_with($rule, 'exists:')) {
            [$_, $table, $identifier] = explode(':', str_replace(',', ':', $rule));

            return ExistsRule::validate($field, $value, $table, $identifier);
        } else {
            throw new \InvalidArgumentException("Unknown validation rule: {$rule}");
        }
    }
}
