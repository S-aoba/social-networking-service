<?php

namespace Validators;

use Validators\Rules\Rule;

class Validator
{
    private array $errors;

    public function __construct(
        private array $rules
    ) {
    }

    public function validate(array $data): array
    {
        $validatedData = [];

        foreach ($this->rules as $field => $rules) {
            $rules = explode('|', $rules);

            foreach ($rules as $rule) {
                $cls = new Rule($field, $data[$field], $rule);
                $result = $this->applyRule($cls);

                if ($result === null) {
                    $this->errors[$field] = $cls->message();
                    break;
                }

                $validatedData[$field] = $result[$field];
            }
        }

        if (empty($this->errors) === false) {
            throw new \InvalidArgumentException(json_encode($this->errors));
        }
        return $validatedData;
    }

    private function applyRule(object $cls): ?array
    {
        return $cls->passes();
    }
}
