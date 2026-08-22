<?php

class AssignmentController extends Controller
{
    public function assignmentInsert()
    {
        if (!Auth::check() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Brak autoryzacji.']);
            exit;
        }

        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            echo json_encode(['success' => false, 'message' => 'Błąd autoryzacji formularza (CSRF).']);
            exit;
        }

        $title = trim($_POST['assignment_title'] ?? '');
        $typeId = (int)($_POST['assignment_type'] ?? 0);
        $points = (float)($_POST['assignment_points'] ?? 0);
        $deadline = $_POST['assignment_deadline'] ?? '';
        $subjectId = (int)($_POST['assignment_subject'] ?? 0);
        $earnedPoints = $_POST['assignment_earned_points'] !== '' ? (float)$_POST['assignment_earned_points'] : null;
        $isCompleted = isset($_POST['assignment_is_completed']) ? 1 : 0;

        if (empty($title) || empty($typeId) || empty($points) || empty($deadline) || empty($subjectId)) {
            echo json_encode(['success' => false, 'message' => 'Wypełnij wszystkie wymagane pola.']);
            exit;
        }

        $assignmentModel = new Assignments();

        $userId = $_SESSION['userID'];
        if (!$assignmentModel->verifySubjectOwnership($subjectId, $userId)) {
            echo json_encode(['success' => false, 'message' => 'Brak dostępu do tego przedmiotu.']);
            exit;
        }

        $newId = $assignmentModel->insertAssignment($subjectId, $typeId, $title, $points, $deadline, $earnedPoints, $isCompleted);

        if ($newId) {
            echo json_encode([
                'success' => true,
                'assignmentId' => $newId
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Wystąpił błąd podczas zapisu do bazy danych.'
            ]);
        }
        exit;
    }
    public function assignmentDelete()
    {
        if (!Auth::check() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit;
        }

        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die('Błąd bezpieczeństwa (CSRF).');
        }

        $assignmentId = (int)($_POST['assignmentId'] ?? 0);
        $subjectId = (int)($_POST['subjectId'] ?? 0);

        $userId = $_SESSION['userID'];

        if ($assignmentId > 0) {
            $assignmentModel = new Assignments();
            if (!$assignmentModel->verifyAssignmentOwnership($assignmentId, $userId)) {
                echo json_encode(['success' => false, 'message' => 'Brak dostępu do tego przedmiotu.']);
                exit;
            }
            $assignmentModel->deleteAssignment($assignmentId);
        }

        if ($subjectId > 0) {
            header('Location: /subject/view/' . $subjectId);
        } else {
            header('Location: /semester/');
        }
        exit;
    }
    public function assignmentUpdate()
    {
        if (!Auth::check() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit;
        }

        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die('Błąd bezpieczeństwa (CSRF).');
        }

        $assignmentId = (int)($_POST['assignmentId'] ?? 0);
        $subjectId = (int)($_POST['subjectId'] ?? 0);
        $userId = $_SESSION['userID'];

        $earnedPoints = $_POST['earned_points'] !== '' ? (float)$_POST['earned_points'] : null;

        $isCompleted = isset($_POST['is_completed']) ? 1 : 0;

        if ($assignmentId > 0) {
            $assignmentModel = new Assignments();
            if (!$assignmentModel->verifyAssignmentOwnership($assignmentId, $userId)) {
                echo json_encode(['success' => false, 'message' => 'Brak dostępu do tego przedmiotu.']);
                exit;
            }
            $assignmentModel->updateProgress($assignmentId, $earnedPoints, $isCompleted);
        }

        if ($subjectId > 0) {
            header('Location: /subject/view/' . $subjectId);
        } else {
            header('Location: /semester/');
        }
        exit;
    }
    public function assignmentUpdateDetails()
    {
        if (!Auth::check() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit;
        }

        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die('Błąd bezpieczeństwa (CSRF).');
        }

        $assignmentId = (int)($_POST['assignmentId'] ?? 0);
        $subjectId = (int)($_POST['subjectId'] ?? 0);
        $teammembers = trim($_POST['teammembers'] ?? '');
        $notes = trim($_POST['notes'] ?? '');
        $userId = $_SESSION['userID'];

        if ($assignmentId > 0) {
            $assignmentModel = new Assignments();
            if (!$assignmentModel->verifyAssignmentOwnership($assignmentId, $userId)) {
                echo json_encode(['success' => false, 'message' => 'Brak dostępu do tego przedmiotu.']);
                exit;
            }
            $assignmentModel->updateAssignmentDetails($assignmentId, $teammembers, $notes);
        }

        if ($subjectId > 0) {
            header('Location: /subject/view/' . $subjectId);
        } else {
            header('Location: /semester/');
        }
        exit;
    }
}