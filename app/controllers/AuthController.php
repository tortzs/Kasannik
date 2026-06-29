<?php

class AuthController extends Controller
{
    public function login(): void
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                'success' => false,
                'message' => 'Nieprawidłowa metoda'
            ]);
            return;
        }

        if (
            !isset($_POST['csrf_token']) ||
            !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
        ) {
            echo json_encode([
                'success' => false,
                'message' => 'Nieprawidłowy token CSRF'
            ]);
            return;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            echo json_encode([
                'success' => false,
                'message' => 'Uzupełnij wszystkie pola'
            ]);
            return;
        }

        $userModel = new User();

        if ($userModel->login($email, $password)) {
            echo json_encode([
                'success' => true,
                'message' => 'Zalogowano poprawnie'
            ]);
            return;
        }

        echo json_encode([
            'success' => false,
            'message' => 'Nieprawidłowy email lub hasło'
        ]);
    }

    public function register(): void
    {
        header('Content-Type: application/json');
        if (
            !isset($_POST['csrf_token']) ||
            !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
        ) {
            echo json_encode([
                'success' => false,
                'message' => 'Nieprawidłowy token CSRF'
            ]);
            return;
        }

        $login = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordRepeat = $_POST['password_repeat'] ?? '';

        if ($login === '' || $email === '' || $password === '' || $passwordRepeat === '') {
            echo json_encode([
                'success' => false,
                'message' => 'Uzupełnij wszystkie pola'
            ]);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                'success' => false,
                'message' => 'Podaj poprawny email'
            ]);
            return;
        }

        if ($password !== $passwordRepeat) {
            echo json_encode([
                'success' => false,
                'message' => 'Hasła nie są takie same'
            ]);
            return;
        }

        $userModel = new User();

        if ($userModel->register($login, $email, $password)) {
            $userModel->login($email, $password);
            echo json_encode([
                'success' => true,
                'message' => 'Konto utworzone poprawnie'
            ]);
            return;
        }

        echo json_encode([
            'success' => false,
            'message' => 'Nie udało się utworzyć konta'
        ]);
    }
    public function logout(): void{
        (new User())->logout();

        header('Location: /');
        exit;
    }


    public function user()
    {
        $this->view("user/index");
    } public function userRegister()
{
    $this->view("user/register");
}
    public function userLogin()
    {
        $this->view("user/login");
    }
    public function userIndex()
    {
        if (!Auth::check()) {
            header('Location: /login');
            exit;
        }

        $userId = (int)$_SESSION['userID'];

        $userModel = new User();
        $user = $userModel->getUserById($userId);

        $this->view("user/index", [
            'user' => $user
        ]);
    }

    public function userUpdate()
    {
        if (!Auth::check() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit;
        }

        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die('Błąd bezpieczeństwa (CSRF).');
        }

        $userId = (int)$_SESSION['userID'];
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['new_password'] ?? '';
        $themePreference = $_POST['theme_preference'] ?? 'Light';
        $avatarFilename = null;

        /*if (empty($username) || empty($email)) {
            die('Nazwa użytkownika i adres e-mail nie mogą być puste.');
        }*/
        if (empty($username) || empty($email)) {
            echo json_encode(['success' => false, 'message' => 'Nazwa użytkownika i adres e-mail nie mogą być puste.']);
            exit;
        }

        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $tmpPath = $_FILES['avatar']['tmp_name'];

            $mime = mime_content_type($tmpPath);
            $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];

            if (in_array($mime, $allowedMimes)) {
                $image = null;
                if ($mime === 'image/jpeg') $image = imagecreatefromjpeg($tmpPath);
                elseif ($mime === 'image/png') $image = imagecreatefrompng($tmpPath);
                elseif ($mime === 'image/webp') $image = imagecreatefromwebp($tmpPath);

                if ($image !== false) {
                    $resizedImage = imagescale($image, 256, 256);

                    $avatarFilename = 'avatar_' . $userId . '_' . uniqid() . '.jpg';
                    $destination = __DIR__ . '/../../public/uploads/avatars/' . $avatarFilename;
                    imagejpeg($resizedImage, $destination, 85);

                    imagedestroy($image);
                    imagedestroy($resizedImage);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Niedozwolony format pliku. Użyj JPG lub PNG.']);
                exit;
            }
        }

        $passwordParam = !empty($password) ? $password : null;

        $userModel = new User();
        $success = $userModel->updateProfile($userId, $username, $email, $passwordParam, $themePreference, $avatarFilename);
        /*try {
            $success = $userModel->updateProfile($userId, $username, $email, $passwordParam);
        } catch (\Throwable $e) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Błąd krytyczny: ' . $e->getMessage()]);
            exit;
        }*/
        header('Content-Type: application/json');
        if ($success) {
            $_SESSION['username'] = $username;
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Wystąpił błąd podczas zapisu w bazie danych.']);
        }
        exit;
    }

}