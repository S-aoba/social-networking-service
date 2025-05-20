<?php

namespace Database\DataAccess\Implementations;

use Database\DatabaseManager;
use Database\DataAccess\Interfaces\UserDAO;

use Models\DataTimeStamp;
use Models\User;

class UserDAOImpl implements UserDAO
{
    // Public
    public function create(User $user, string $password): bool
    {
        if ($user->getId() !== null) throw new \Exception('Cannot create a user with an existing ID. id: ' . $user->getId());

        $mysqli = DatabaseManager::getMysqliConnection();

        $query = "INSERT INTO users (email, password) VALUES (?, ?)";

        $result = $mysqli->prepareAndExecute(
            $query,
            'ss',
            [
                $user->getEmail(),
                password_hash($password, PASSWORD_DEFAULT), // store the hashed password
            ]
        );

        if (!$result) return false;

        $user->setId($mysqli->insert_id);

        return true;
    }

    public function updateEmailConfirmedAt(int $userId): void {
        $this->updateRowEmailConfirmedAt($userId);
    }

    public function getById(int $id): ?User
    {
        $userRaw = $this->getRawById($id);
        if($userRaw === null) return null;

        return $this->rawDataToUser($userRaw);
    }

    public function getByEmail(string $email): ?User
    {
        $userRaw = $this->getRawByEmail($email);
        if($userRaw === null) return null;

        return $this->rawDataToUser($userRaw);
    }

    public function getHashedPasswordById(int $id): ?string
    {
        return $this->getRawById($id)['password']??null;
    }

    // Private
    private function getRawById(int $id): ?array{
        $mysqli = DatabaseManager::getMysqliConnection();

        $query = "SELECT * FROM users WHERE id = ?";

        $result = $mysqli->prepareAndFetchAll($query, 'i', [$id])[0] ?? null;

        if ($result === null) return null;

        return $result;
    }

    private function getRawByEmail(string $email): ?array{
        $mysqli = DatabaseManager::getMysqliConnection();

        $query = "SELECT * FROM users WHERE email = ?";

        $result = $mysqli->prepareAndFetchAll($query, 's', [$email])[0] ?? null;

        if ($result === null) return null;
        return $result;
    }

    private function updateRowEmailConfirmedAt(int $userId): void {
        $mysqli = DatabaseManager::getMysqliConnection();

        date_default_timezone_set('Asia/Tokyo');
        $currentDateTime = date('Y-m-d H:i:s');
        $query = "UPDATE users SET email_confirmed_at = ? WHERE id = ?";

        $mysqli->prepareAndExecute($query, 'si', [$currentDateTime, $userId]);
    }

    private function rawDataToUser(array $rawData): User{
        return new User(
            email: $rawData['email'],
            id: $rawData['id'],
            company: $rawData['company'] ?? null,
            timeStamp: new DataTimeStamp($rawData['created_at'], $rawData['updated_at'])
        );
    }
}