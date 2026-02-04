<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class UserRepository
{
    public static function findByEmail(string $email): ?array
    {
        $sql = 'SELECT id, name, email, password FROM users WHERE email = :email LIMIT 1';
        $stmt = Database::connection()->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $row = $stmt->fetch();

        return $row ?: null;
    }

    public static function insertUser(string $name, string $email, string $passwordHash): int
    {
        $sql = 'INSERT INTO users (name, email, password) VALUES (:name, :email, :password) RETURNING id';
        $stmt = Database::connection()->prepare($sql);
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':password', $passwordHash);
        $stmt->execute();

        return (int)$stmt->fetchColumn();
    }
}
