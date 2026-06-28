<?php

class AssignmentController
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

        if ($assignmentId > 0) {
            $assignmentModel = new Assignments();
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

        $earnedPoints = $_POST['earned_points'] !== '' ? (float)$_POST['earned_points'] : null;

        $isCompleted = isset($_POST['is_completed']) ? 1 : 0;

        if ($assignmentId > 0) {
            $assignmentModel = new Assignments();
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
        $team = trim($_POST['team'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if ($assignmentId > 0) {
            $assignmentModel = new Assignments();
            $assignmentModel->updateAssignmentDetails($assignmentId, $team, $description);
        }

        if ($subjectId > 0) {
            header('Location: /subject/view/' . $subjectId);
        } else {
            header('Location: /semester/');
        }
        exit;
    }
}