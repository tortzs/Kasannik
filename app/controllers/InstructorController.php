<?php

class InstructorController extends Controller
{
    public function instructor()
    {
        $instructors = new Instructors();
        $data = [
            'instructors' => $instructors->getAll(),
        ];
        $this->view("instructor/index", $data);
    }

    public function instructorEdit()
    {
        $instructors = new Instructors();

        $instructorId = filter_input(INPUT_GET, 'instructorId', FILTER_VALIDATE_INT);

        if (!$instructorId) {
            header('Location: /instructor');
            exit;
        }

        $instructor = $instructors->instructorCheck($instructorId);

        if (!$instructor) {
            header('Location: /instructor');
            exit;
        }

        $data = [
            'instructor' => $instructors->getById($instructorId),
        ];

        $this->view("instructor/edit", $data);
    }

    public function instructorInsert(): void
    {
        header('Content-Type: application/json');

        if (!Auth::check()) {
            echo json_encode([
                'success' => false,
                'message' => 'Musisz być zalogowany'
            ]);
            return;
        }

        if (
            !isset($_POST['csrf_token'], $_SESSION['csrf_token']) ||
            !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
        ) {
            echo json_encode([
                'success' => false,
                'message' => 'Nieprawidłowy token CSRF'
            ]);
            return;
        }

        $academicTitle = trim($_POST['academic_title'] ?? '');
        $firstName = trim($_POST['first_name'] ?? '');
        $lastName = trim($_POST['last_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $room = trim($_POST['room'] ?? '');

        if (
            $firstName === '' ||
            $lastName === ''
        ) {
            echo json_encode([
                'success' => false,
                'message' => 'Uzupełnij wszystkie obowiązkowe pola'
            ]);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($email)) {
            echo json_encode([
                'success' => false,
                'message' => 'Podaj poprawny email'
            ]);
            return;
        }

        $instructorsModel = new Instructors();

        $inserted = $instructorsModel->instructorInsert(
            $academicTitle,
            $firstName,
            $lastName,
            $email,
            $room
        );

        if ($inserted) {
            echo json_encode([
                'success' => true,
                'message' => 'Prowadzący został dodany poprawnie',
                'action' => $_POST['action'] ?? 'save'
            ]);
            return;
        }

        echo json_encode([
            'success' => false,
            'message' => 'Nie udało się dodać prowadzącego'
        ]);
    }
    public function instructorUpdate(): void
    {
        header('Content-Type: application/json');

        if (!Auth::check()) {
            echo json_encode([
                'success' => false,
                'message' => 'Musisz być zalogowany'
            ]);
            return;
        }

        if (
            !isset($_POST['csrf_token'], $_SESSION['csrf_token']) ||
            !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
        ) {
            echo json_encode([
                'success' => false,
                'message' => 'Nieprawidłowy token CSRF'
            ]);
            return;
        }

        $id = filter_input(INPUT_POST, 'instructorId', FILTER_VALIDATE_INT);
        $academicTitle = trim($_POST['academic_title'] ?? '');
        $firstName = trim($_POST['first_name'] ?? '');
        $lastName = trim($_POST['last_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $room = trim($_POST['room'] ?? '');

        if (
            $firstName === '' ||
            $lastName === ''
        ) {
            echo json_encode([
                'success' => false,
                'message' => 'Uzupełnij wszystkie obowiązkowe pola'
            ]);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($email)) {
            echo json_encode([
                'success' => false,
                'message' => 'Podaj poprawny email'
            ]);
            return;
        }

        $instructorsModel = new Instructors();
        $updated = $instructorsModel->instructorEdit(
            $id,
            $academicTitle,
            $firstName,
            $lastName,
            $email,
            $room
        );

        if ($updated) {
            echo json_encode([
                'success' => true,
                'message' => 'Prowadzący został dodany poprawnie',
                'action' => $_POST['action'] ?? 'save'
            ]);
            return;
        }

        echo json_encode([
            'success' => false,
            'message' => 'Nie udało się dodać prowadzącego'
        ]);
    }

    public function instructorDelete(): void
    {
        if (!Auth::check()) {
            header('Location: /login');
            exit;
        }

        if (
            !isset($_POST['csrf_token'], $_SESSION['csrf_token']) ||
            !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
        ) {
            header('Location: /instructor');
            exit;
        }

        $instructorId = filter_input(INPUT_POST, 'instructorId', FILTER_VALIDATE_INT);

        if (!$instructorId) {
            header('Location: /instructor');
            exit;
        }

        $instructors = new Instructors();
        $instructors->instructorDelete($instructorId);

        header('Location: /instructor');
        exit;
    }

    public function instructorAdd()
    {
        $this->view("instructor/add");
    }
}