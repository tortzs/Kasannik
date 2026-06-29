<?php

class TodoController extends Controller
{
    public function todoIndex()
    {
        if (!Auth::check()) {
            header('Location: /login');
            exit;
        }

        $userId = (int)$_SESSION['userID'];

        $todoModel = new Todo();
        $tasks = $todoModel->getTasksByUserId($userId);

        $this->view('todo/index', [
            'tasks' => $tasks
        ]);
    }
}