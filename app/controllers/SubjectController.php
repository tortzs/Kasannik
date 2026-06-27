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
        $data = [
          'subject' => $subject,
        ];
        $this->view("subject/index", $data);

    }

}