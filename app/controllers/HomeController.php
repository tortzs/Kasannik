<?php

class HomeController extends Controller
{

    public function index()
    {

        $this->view("home/index");
    }

    public function dashboard()
    {
        $userId = (int)$_SESSION['userID'];

        $assignmentModel = new Assignments();
        $todoModel = new Todo();
        $scheduleModel = new Schedule();
        $tomorrow = date('Y-m-d', strtotime('+1 day'));

        $this->view("home/dashboard", [
            'upcomingAssignments' => $assignmentModel->getUpcomingAssignments($userId, 3),
            'upcomingTodos'       => $todoModel->getUpcomingTodos($userId, 3),
            'classesTomorrow'     => $scheduleModel->getClassesForDate(
                $userId,
                $tomorrow
            )
        ]);
    }
}