<?php

class UserModel
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    //create user

    public function createUser(string $name, string $email, string $passwordHash): bool
    {
        $query = "
            INSERT INTO users (
                name,
                email,
                password
            )
            VALUES (
                :name,
                :email,
                :password
            )
        ";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $passwordHash
        ]);
    }

    //find user by email

    public function findByEmail(string $email): ?array
    {
        $query = "
            SELECT * FROM users
            WHERE email = :email
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([':email' => $email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }
}
