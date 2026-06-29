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


}