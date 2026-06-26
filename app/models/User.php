<?php

class User extends Model
{
    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM Users");

        return $stmt->fetchAll();
    }

    public function login(string $email, string $password): bool
    {
        $stmt = $this->pdo->prepare("SELECT * FROM Users WHERE Email = :email LIMIT 1");
        $stmt->bindValue(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['PasswordHash'])) {
            return false;
        }

        $_SESSION['isLoggedIn'] = true;
        $_SESSION['userID'] = $user['ID'];

        return true;
    }

    public function register(string $login, string $email, string $password): bool
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare("
            INSERT INTO Users (Username, Email, PasswordHash) 
            VALUES (:login, :email, :password)
        ");

        $stmt->bindValue(':login', $login);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':password', $hashedPassword);

        return $stmt->execute();
    }
    public function logout(){
        unset($_SESSION['isLoggedIn']);
        unset($_SESSION['userID']);
        return true;
    }
}