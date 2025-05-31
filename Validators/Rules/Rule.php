<?php

namespace Validators\Rules;

use Database\DataAccess\DAOFactory;
use Helpers\Authenticate;

class Rule
{
  public function __construct(
    private string $field,
    private $data,
    private string $rule
  )
  {}

  public function passes(): ?array {
    $ruleName = explode(':', $this->rule)[0];
    
    return match ($ruleName) {
      'required' => $this->required(),
      'string' => $this->string(),
      'int' => $this->int(),
      'min' => $this->min(),
      'max' => $this->max(),
      'exists' => $this->exists()
    };
  }

  public function message(): string {
    $ruleName = explode(':', $this->rule)[0];
    return match ($ruleName) {
      'required' => $this->requiredMessage(),
      'string' => $this->stringMessage(),
      'int' => $this->intMessage(),
      'min' => $this->minMessage(),
      'max' => $this->maxMessage(),
      'exists' => $this->existsMessage()
    };
  }

  private function requiredMessage(): string {
    return "{$this->field} is required.";
  }

  private function required() : ?array {
    return isset($this->data) && $this->data !== '' ? [$this->field => $this->data] : null;
  }

  private function string(): ?array {
    return is_string($this->data) ? [$this->field => $this->data] : null;
  }

  private function stringMessage(): string {
    return "{$this->field} must be a string.";
  }

  private function int(): ?array {
    return filter_var($this->data, FILTER_VALIDATE_INT) !== false ?
                [$this->field => (int)$this->data]
                :
                null;
  }

  private function intMessage(): string {
    return "{$this->field} must be an integer.";
  }

  private function min(): ?array {
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

  private function minMessage(): string {
    $min = explode(':', $this->rule)[1];

    if(is_string($this->data)) {      
      return "{$this->field} must be at least {$min} characters.";
    }

    if (is_int($value) || ctype_digit((string)$value)) {
      return "{$this->field} must be at least {$min}.";
    }

    throw new \InvalidArgumentException("{$field} must be a string or integer for min validation.");
  }
  
  private function max(): ?array {
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

  private function maxMessage(): string {
    $max = explode(':', $this->rule)[1];

    if(is_string($this->data)) {      
      return "{$this->field} must be at most {$max} characters.";
    }

    if (is_int($value) || ctype_digit((string)$value)) {
      return "{$this->field} must be at most {$max}.";
    }

    throw new \InvalidArgumentException("{$field} must be a string or integer for min validation.");
  }

  private function exists(): ?array {
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

  private function existsMessage(): string {
    [$_, $table, $_] = explode(':', str_replace(',', ':', $this->rule));

    if($table === 'users') {
      return "{$this->data} does not exist.";
    }
    else if($table === 'posts') {
      return "Post does not exist.";
    }
    else if($table === 'conversations') {
      return "Conversation does not exist.";
    }
    else {
      throw new \InvalidArgumentException("Invalid table specified in validation rule: {$table}.");
    }

  }

}