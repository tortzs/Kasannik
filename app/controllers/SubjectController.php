<?php

class SubjectController extends Controller
{
    public function subjectInsert(): void
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

        $semesterId = filter_input(INPUT_POST, 'subject_semester', FILTER_VALIDATE_INT);
        $instructorId = filter_input(INPUT_POST, 'subject_instructor', FILTER_VALIDATE_INT);
        $name = trim($_POST['subject_name'] ?? '');
        $ects = trim($_POST['subject_ects'] ?? '');

        if ($name === '' || $semesterId === '' || $instructorId === '') {
            echo json_encode([
                'success' => false,
                'message' => 'Uzupełnij wszystkie obowiązkowe pola'
            ]);
            return;
        }



        $subjectsModel = new Subjects();

        $inserted = $subjectsModel->subjectInsert(
            $semesterId,
            $instructorId,
            $name,
            $ects,
        );

        if ($inserted['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'Semestr został dodany poprawnie',
                'subjectId' => $inserted['subjectId']
            ]);
            return;
        }

        echo json_encode([
            'success' => false,
            'message' => 'Nie udało się dodać semestru'
        ]);
    }

    public function subjectDelete(): bool
    {
        if (!Auth::check()) {
            header('Location: /login');
            exit;
        }
        $subjectId = filter_input(INPUT_POST, 'subjectId', FILTER_VALIDATE_INT);
        $semesterId = filter_input(INPUT_POST, 'semesterId', FILTER_VALIDATE_INT);

        if (
            !isset($_POST['csrf_token'], $_SESSION['csrf_token']) ||
            !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
        ) {
            header('Location: /semester/view/'.$semesterId);
            exit;
        }


        if (!$semesterId) {
            header('Location: /semester');
            exit;
        }

        $subjects = new Subjects();
        $subjects->subjectDelete($subjectId, $semesterId);

        header('Location: /semester/view/'.$semesterId);
        exit;
    }
    public function subjectView(int $subjectId)
    {
        if (!Auth::check()) {
            header('Location: /login');
            exit;
        }

        if (!$subjectId) {
            header('Location: /semester/');
            exit;
        }
        $subjectModel = new Subjects();
        $subject = $subjectModel->getSubjectById($subjectId);

        $assignmentsModel = new Assignments();
        $assignments = $assignmentsModel->getAssignmentsBySubject($subjectId);

        $assignmentTypesModel = new AssignmentTypes();
        $types = $assignmentTypesModel->getAllTypes();

        $data = [
            'subject' => $subject,
            'assignments'     => $assignments,
            'assignmentTypes' => $types,
        ];
        $this->view("subject/view", $data);

    }
    public function subjectUpdate()
    {
        if (!Auth::check() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit;
        }

        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die('Błąd bezpieczeństwa (CSRF).');
        }

        $subjectId = (int)($_POST['subjectId'] ?? 0);
        $semesterId = (int)($_POST['semesterId'] ?? 0);

        $maxPoints = $_POST['max_points'] !== '' ? (float)str_replace(',', '.', $_POST['max_points']) : null;

        $description = trim($_POST['description'] ?? '');

        if ($subjectId > 0) {
            $subjectModel = new Subjects();
            $subjectModel->updateSubjectDetails($subjectId, $maxPoints, $description);
        }

        if ($semesterId > 0) {
            header('Location: /semester/view/' . $semesterId);
        } else {
            header('Location: /semester/');
        }
        exit;
    }
}