<?php

class SemesterController extends Controller
{
    public function semester()
    {
        $semesters = new semesters();
        $data = [
            'semesters' => $semesters->getAll(),
        ];
        $this->view("semester/index", $data);
    }
    public function semesterView(string $semesterId)
    {
        $instructors = new Instructors();
        $instructorsList = $instructors->getAll();
        $semesters = new semesters();
        if($semesterId != 'current') {
            $currentSemester = $semesters->getById($semesterId);
        }else{
            $currentSemester = $semesters->getCurrent();
            $semesterId = $currentSemester['ID'];
        }
        if($currentSemester == null) {
            header('Location: /semester');
            exit;
        }
        $subjects = new subjects();
        $subjectsList = $subjects->getSubjectsBySemester(intval($semesterId));
        $data = [
            'semester' => $currentSemester,
            'semesterId' => $semesterId,
            'subjects' => $subjectsList,
            'instructors' => $instructorsList,
        ];
        $this->view("semester/view", $data);
    }

    public function semesterEdit(string $semesterId)
    {
        $semesters = new semesters();


        if (!$semesterId) {
            header('Location: /semester');
            exit;
        }

        $semester = $semesters->semesterCheck($semesterId);

        if (!$semester) {
            header('Location: /semester');
            exit;
        }

        $data = [
            'semester' => $semesters->getById($semesterId),
        ];

        $this->view("semester/edit", $data);
    }

    public function semesterInsert(): void
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

        $name = trim($_POST['name'] ?? '');
        $startDate = trim($_POST['start_date'] ?? '');
        $endDate = trim($_POST['end_date'] ?? '');

        if ($name === '' || $startDate === '' || $endDate === '') {
            echo json_encode([
                'success' => false,
                'message' => 'Uzupełnij wszystkie obowiązkowe pola'
            ]);
            return;
        }

        $startDateObj = DateTime::createFromFormat('Y-m-d', $startDate);
        $endDateObj = DateTime::createFromFormat('Y-m-d', $endDate);

        if (
            !$startDateObj ||
            !$endDateObj ||
            $startDateObj->format('Y-m-d') !== $startDate ||
            $endDateObj->format('Y-m-d') !== $endDate
        ) {
            echo json_encode([
                'success' => false,
                'message' => 'Podaj poprawne daty'
            ]);
            return;
        }

        if ($startDateObj > $endDateObj) {
            echo json_encode([
                'success' => false,
                'message' => 'Data rozpoczęcia nie może być późniejsza niż data zakończenia'
            ]);
            return;
        }

        $semestersModel = new Semesters();

        $inserted = $semestersModel->semesterInsert(
            $name,
            $startDate,
            $endDate,
        );

        if ($inserted) {
            echo json_encode([
                'success' => true,
                'message' => 'Semestr został dodany poprawnie',
                'action' => $_POST['action'] ?? 'save'
            ]);
            return;
        }

        echo json_encode([
            'success' => false,
            'message' => 'Nie udało się dodać semestru'
        ]);
    }
    public function semesterUpdate(): void
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
        $id = filter_input(INPUT_POST, 'semesterId', FILTER_VALIDATE_INT);
        $name = trim($_POST['name'] ?? '');
        $startDate = trim($_POST['start_date'] ?? '');
        $endDate = trim($_POST['end_date'] ?? '');

        if ($name === '' || $startDate === '' || $endDate === '') {
            echo json_encode([
                'success' => false,
                'message' => 'Uzupełnij wszystkie obowiązkowe pola'
            ]);
            return;
        }


        $semestersModel = new semesters();
        $updated = $semestersModel->semesterEdit(
            $id,
            $name,
            $startDate,
            $endDate,
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

    public function semesterDelete(): void
    {
        if (!Auth::check()) {
            header('Location: /login');
            exit;
        }

        if (
            !isset($_POST['csrf_token'], $_SESSION['csrf_token']) ||
            !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
        ) {
            header('Location: /semester');
            exit;
        }

        $semesterId = filter_input(INPUT_POST, 'semesterId', FILTER_VALIDATE_INT);

        if (!$semesterId) {
            header('Location: /semester');
            exit;
        }

        $semesters = new semesters();
        $semesters->semesterDelete($semesterId);

        header('Location: /semester');
        exit;
    }

    public function semesterAdd()
    {
        $this->view("semester/add");
    }
}