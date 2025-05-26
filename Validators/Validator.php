<?php

namespace Validators;

use Database\DataAccess\DAOFactory;
use Exception;
use Helpers\Authenticate;

class Validator
{
  public function __construct(
    // array<$field, string[rules]>
    private array $rules
  )
  {}

  public function validate(array $data): array
  {
    $validatedData = [];
 
    foreach($this->rules as $field => $rules)
    {
      $rules = explode('|', $rules);
      
      foreach($rules as $rule) {
        $validatedValue = $this->applyRule($field, $data[$field], $rule);

        $validatedData[$field] = $validatedValue[$field];
      }
    }
    return $validatedData;
  }


  private function applyRule(string $field, $value, string $rule): array
  {
    $validatedData = [];

    if ($rule === 'required') {
        return isset($value) && $value !== '' ? [$field => $value] : throw new \InvalidArgumentException("{$field} is required.");
    }
    else if ($rule === 'int') {
        return filter_var($value, FILTER_VALIDATE_INT) !== false ? [$field => (int)$value] : throw new \InvalidArgumentException("{$field} must be an integer.");
    }
    else if ($rule === 'nullable') {
        return [$field => $value ?? null];
    }
    else if (preg_match('/^min:(\d+)$/', $rule, $matches)) {
        $min = (int)$matches[1];
        if (isset($value) && mb_strlen($value) < $min) {
            throw new \InvalidArgumentException("{$field} must be at least {$min} characters.");
        }
        return [$field => $value];
    }
    else if (preg_match('/^max:(\d+)$/', $rule, $matches)) {
        $max = (int)$matches[1];
        if (isset($value) && mb_strlen($value) > $max) {
            throw new \InvalidArgumentException("{$field} must be at most {$max} characters.");
        }
        return [$field => $value];
    }
    else if (str_starts_with($rule, 'exists:')) {
        [$_, $table, $identifier] = explode(':', str_replace(',', ':', $rule));
        
        if ($table === 'users') {
          $profileDAO = DAOFactory::getProfileDAO();
          $profile = null;
          
          switch ($identifier) {
            case 'id':
              $profile = $profileDAO->getByUserId($value);
              break;
            case 'username':
              $profile = $profileDAO->getByUsername($value);
              break;
            default:
              throw new Exception('Identifier is not valid: ' . $identifier);
          }
          $validatedData[$field] = isset($profile) ? 
              $profile : 
              throw new \InvalidArgumentException("{$value} is not exists.");
        }
        else if ($table === 'posts') {
            $user = Authenticate::getAuthenticatedUser();
            $postDAO = DAOFactory::getPostDAO();
            $post = $postDAO->getById($value, $user->getId());
            $validatedData[$field] = isset($post) ? $post : throw new \InvalidArgumentException("Post is not exists.");
        }
        else if ($table === 'conversations') {
            $conversationDAO = DAOFactory::getConversationDAO();
            $conversation = $conversationDAO->findByConversationId($value);
            $validatedData[$field] = isset($conversation) ? $conversation : throw new \InvalidArgumentException("conversation is not exists.");
        }
        else throw new Exception('Identifier is not valid: ' . $identifier);

        return $validatedData;
    }

    return $validatedData;
  }
}