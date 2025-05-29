<?php

namespace Validators\Rules;

use Database\DataAccess\DAOFactory;
use Helpers\Authenticate;

class ExistsRule
{
    public static function validate(string $field, $value, string $table, string $identifier): array
    {
        if ($table === 'users') {
            $profileDAO = DAOFactory::getProfileDAO();
            $profile = match ($identifier) {
                'id' => $profileDAO->getByUserId($value),
                'username' => $profileDAO->getByUsername($value),
                default => throw new \InvalidArgumentException('Identifier is not valid: ' . $identifier),
            };
            return isset($profile) ? [$field => $profile] : throw new \InvalidArgumentException("{$value} does not exist.");
        } elseif ($table === 'posts') {
            $user = Authenticate::getAuthenticatedUser();
            $postDAO = DAOFactory::getPostDAO();
            $post = $postDAO->getById($value, $user->getId());
            return isset($post) ? [$field => $post] : throw new \InvalidArgumentException("Post does not exist.");
        } elseif ($table === 'conversations') {
            $conversationDAO = DAOFactory::getConversationDAO();
            $conversation = $conversationDAO->findByConversationId($value);
            return isset($conversation) ? [$field => $conversation] : throw new \InvalidArgumentException("Conversation does not exist.");
        } else {
            throw new \InvalidArgumentException("Invalid table specified in validation rule: {$table}.");
        }
    }
}
