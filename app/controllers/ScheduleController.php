<?php

class ScheduleController extends Controller
{



    public function scheduleIndex()
    {
        if (!Auth::check()) {
            header('Location: /login');
            exit;
        }

        $userId = (int)$_SESSION['userID'];

        $scheduleModel = new Schedule();
        $events = $scheduleModel->getActiveSemesterSchedule($userId);
        $semester = $scheduleModel->getActiveSemesterInfo($userId);

        $this->view("schedule/index", [
            'events' => $events,
            'semester' => $semester
        ]);
    }

    public function scheduleDeadlines(){
        $this->view("schedule/deadlines");
    }

    public function scheduleEdit()
    {
        if (!Auth::check()) {
        header('Location: /login');
        exit;
    }

        $userId = (int)$_SESSION['userID'];
        $scheduleModel = new Schedule();
        $subjects = $scheduleModel->getActiveSemesterSubjects($userId);
        $events = $scheduleModel->getActiveSemesterSchedule($userId);

        $this->view("schedule/edit", [
            'subjects' => $subjects,
            'events'   => $events
        ]);
    }
    public function addEvent()
    {
        if (!Auth::check() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /schedule/edit');
            exit;
        }

        $subjectId = (int)$_POST['subject_id'];
        $dayOfWeek = (int)$_POST['day_of_week'];
        $startTime = $_POST['start_time'];
        $endTime   = $_POST['end_time'];
        $room      = trim($_POST['room'] ?? '');
        $classType = trim($_POST['class_type'] ?? 'WYK');
        $weekType  = trim($_POST['week_type'] ?? 'every');

        if ($subjectId > 0 && $dayOfWeek > 0 && !empty($startTime) && !empty($endTime)) {
            $scheduleModel = new Schedule();
            $scheduleModel->addEvent($subjectId, $dayOfWeek, $startTime, $endTime, $room, $classType, $weekType);
        }

        header('Location: /schedule/edit');
        exit;
    }

    public function deleteEvent()
    {
        if (!Auth::check() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /schedule/edit');
            exit;
        }

        $eventId = (int)$_POST['event_id'];
        if ($eventId > 0) {
            $scheduleModel = new Schedule();
            $scheduleModel->deleteEvent($eventId, Auth::id());
        }

        header('Location: /schedule/edit');
        exit;
    }
    
}