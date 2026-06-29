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
        $this->view("schedule/edit");
    }

    
}