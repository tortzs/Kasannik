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

    public function updateProfile(int $userId, string $username, string $email, ?string $password = null, string $themePreference = 'Light'): bool
    {
        if ($password !== null) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $this->pdo->prepare("
                UPDATE Users 
                SET Username = :username, 
                    Email = :email, 
                    PasswordHash = :password,
                    ThemePreference = :theme
                WHERE ID = :id
            ");

            return $stmt->execute([
                'username' => $username,
                'email'    => $email,
                'password' => $hashedPassword,
                'theme'    => $themePreference,
                'id'       => $userId
            ]);
        } else {
            $stmt = $this->pdo->prepare("
                UPDATE Users 
                SET Username = :username, 
                    Email = :email,
                    ThemePreference = :theme
                WHERE ID = :id
            ");

            return $stmt->execute([
                'username' => $username,
                'email'    => $email,
                'theme'    => $themePreference,
                'id'       => $userId
            ]);
        }
    }
    public function getUserById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT ID, Username, Email, CreatedAt, ThemePreference 
            FROM Users 
            WHERE ID = :id
        ");

        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }
}