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
        session_regenerate_id(true);
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['userID'] = $user['ID'];
        $_SESSION['username'] = $user['Username'];
        $_SESSION['avatar'] = $user['Avatar'];



        $stmtSem = $this->pdo->prepare("SELECT Name FROM Semesters WHERE UserID = :userId AND IsCurrent = 1 LIMIT 1");
        $stmtSem->execute(['userId' => $user['ID']]);
        $activeSemesterName = $stmtSem->fetchColumn();
        $_SESSION['active_semester_name'] = $activeSemesterName ? $activeSemesterName : null;

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
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = array();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();

        header('Location: /login');
        exit;
    }

    public function updateProfile(int $userId, string $username, string $email, ?string $password = null, string $themePreference = 'Light', ?string $avatarFilename = null): bool
    {
        if ($avatarFilename !== null) {
            $stmtAvatar = $this->pdo->prepare("UPDATE Users SET Avatar = :avatar WHERE ID = :id");
            $stmtAvatar->execute([
                'avatar' => $avatarFilename,
                'id'     => $userId
            ]);

            // Opcjonalnie: Zapisujemy do sesji, żeby sidebar od razu zaktualizował zdjęcie!
            $_SESSION['avatar'] = $avatarFilename;
        }
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