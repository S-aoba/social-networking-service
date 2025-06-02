<?php

namespace Validators\Rules;

use Database\DataAccess\DAOFactory;
use Helpers\Authenticate;

class Rule
{
    private const RULE_METHODS = [
        'required' => 'required',
        'string'   => 'string',
        'int'      => 'int',
        'min'      => 'min',
        'max'      => 'max',
        'exists'   => 'exists',
        'email'    => 'email'
    ];

    private const MESSAGE_METHODS = [
        'required' => 'requiredMessage',
        'string'   => 'stringMessage',
        'int'      => 'intMessage',
        'min'      => 'minMessage',
        'max'      => 'maxMessage',
        'exists'   => 'existsMessage',
        'email'    => 'emailMessage'
    ];

    public function __construct(
        private string $field,
        private $data,
        private string $rule
    ) {
    }

    public function passes(): ?array
    {
        $ruleName = explode(':', $this->rule)[0];
        $method = self::RULE_METHODS[$ruleName] ?? null;
        if (!$method || !method_exists($this, $method)) {
            throw new \InvalidArgumentException("Unknown validation rule: {$ruleName}");
        }
        return $this->$method();
    }

    public function message(): string
    {
        $ruleName = explode(':', $this->rule)[0];
        $method = self::MESSAGE_METHODS[$ruleName] ?? null;
        if (!$method || !method_exists($this, $method)) {
            throw new \InvalidArgumentException("Unknown validation rule: {$ruleName}");
        }
        return $this->$method();
    }

    private function requiredMessage(): string
    {
        return "{$this->field} is required.";
    }

    private function required(): ?array
    {
        return isset($this->data) && $this->data !== '' ? [$this->field => $this->data] : null;
    }

    private function string(): ?array
    {
        return is_string($this->data) ? [$this->field => $this->data] : null;
    }

    private function stringMessage(): string
    {
        return "{$this->field} must be a string.";
    }

    private function int(): ?array
    {
        return filter_var($this->data, FILTER_VALIDATE_INT) !== false ?
                    [$this->field => (int)$this->data]
                    :
                    null;
    }

    private function intMessage(): string
    {
        return "{$this->field} must be an integer.";
    }

    private function min(): ?array
    {
        $min = explode(':', $this->rule)[1];

        if (is_string($this->data)) {
            if (mb_strlen($this->data) < $min) {
                return null;
            }
            return [$this->field => $this->data];
        }

        if (is_int($this->data) || ctype_digit((string)$this->data)) {
            if ((int)$this->data < $min) {
                return null;
            }
            return [$this->field => (int)$this->data];
        }

        throw new \InvalidArgumentException("{$this->field} must be a string or integer for min validation.");
    }

    private function minMessage(): string
    {
        $min = explode(':', $this->rule)[1];

        if (is_string($this->data)) {
            return "{$this->field} must be at least {$min} characters.";
        }

        if (is_int($this->data) || ctype_digit((string)$this->data)) {
            return "{$this->field} must be at least {$min}.";
        }

        throw new \InvalidArgumentException("{$this->field} must be a string or integer for min validation.");
    }

    private function max(): ?array
    {
        $max = explode(':', $this->rule)[1];

        if (is_string($this->data)) {
            if (mb_strlen($this->data) > $max) {
                return null;
            }
            return [$this->field => $this->data];
        }

        if (is_int($this->data) || ctype_digit((string)$this->data)) {
            if ((int)$this->data > $max) {
                return null;
            }
            return [$this->field => (int)$this->data];
        }

        throw new \InvalidArgumentException("{$this->field} must be a string or integer for min validation.");
    }

    private function maxMessage(): string
    {
        $max = explode(':', $this->rule)[1];

        if (is_string($this->data)) {
            return "{$this->field} must be at most {$max} characters.";
        }

        if (is_int($this->data) || ctype_digit((string)$this->data)) {
            return "{$this->field} must be at most {$max}.";
        }

        throw new \InvalidArgumentException("{$this->field} must be a string or integer for min validation.");
    }

    private function exists(): ?array
    {
        [$_, $table, $identifier] = explode(':', str_replace(',', ':', $this->rule));

        if ($table === 'users') {
            $profileDAO = DAOFactory::getProfileDAO();
            $profile = match ($identifier) {
                'id' => $profileDAO->getByUserId($this->data),
                'username' => $profileDAO->getByUsername($this->data),
                default => throw new \InvalidArgumentException('Identifier is not valid: ' . $identifier),
            };
            return isset($profile) ? [$this->field => $profile] : null;
        } elseif ($table === 'posts') {
            $user = Authenticate::getAuthenticatedUser();
            $postDAO = DAOFactory::getPostDAO();
            $post = $postDAO->getById($this->data, $user->getId());
            return isset($post) ? [$this->field => $post] : null;
        } elseif ($table === 'conversations') {
            $conversationDAO = DAOFactory::getConversationDAO();
            $conversation = $conversationDAO->findByConversationId($this->data);
            return isset($conversation) ? [$this->field => $conversation] : null;
        } else {
            throw new \InvalidArgumentException("Invalid table specified in validation rule: {$table}.");
        }
    }

    private function existsMessage(): string
    {
        [$_, $table, $_] = explode(':', str_replace(',', ':', $this->rule));

        if ($table === 'users') {
            return "{$this->data} does not exist.";
        } elseif ($table === 'posts') {
            return "Post does not exist.";
        } elseif ($table === 'conversations') {
            return "Conversation does not exist.";
        } else {
            throw new \InvalidArgumentException("Invalid table specified in validation rule: {$table}.");
        }

    }

    private function email(): ?array
    {
        return filter_var($this->data, FILTER_VALIDATE_EMAIL) ? [$this->field => $this->data] : null;
    }

    private function emailMessage(): string
    {
        return "{$this->field} must be a valid email address.";
    }

}
