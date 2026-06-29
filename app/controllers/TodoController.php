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
    public function todoAdd()
    {
        if (!Auth::check() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /todo');
            exit;
        }

        $userId = (int)$_SESSION['userID'];
        $taskDesc = trim($_POST['task_desc'] ?? '');
        $targetDate = !empty($_POST['target_date']) ? $_POST['target_date'] : null;

        if ($taskDesc !== '') {
            $todoModel = new Todo();
            $todoModel->addTask($userId, $taskDesc, $targetDate);
        }

        header('Location: /todo');
        exit;
    }
    public function todoToggle()
    {
        if (!Auth::check() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /todo');
            exit;
        }

        $userId = (int)$_SESSION['userID'];
        $taskId = (int)$_POST['task_id'];
        $newStatus = (int)$_POST['current_status'] === 1 ? 0 : 1;

        $todoModel = new Todo();
        $todoModel->toggleTaskStatus($taskId, $userId, $newStatus);

        header('Location: /todo');
        exit;
    }
    public function todoDelete()
    {
        if (!Auth::check() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /todo');
            exit;
        }

        $userId = (int)$_SESSION['userID'];
        $taskId = (int)$_POST['task_id'];

        $todoModel = new Todo();
        $todoModel->deleteTask($taskId, $userId);

        header('Location: /todo');
        exit;
    }
    public function todoEdit()
    {
        if (!Auth::check() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /todo');
            exit;
        }

        $userId = (int)$_SESSION['userID'];
        $taskId = (int)$_POST['task_id'];
        $taskDesc = trim($_POST['task_desc'] ?? '');
        $targetDate = !empty($_POST['target_date']) ? $_POST['target_date'] : null;

        if ($taskDesc !== '' && $taskId > 0) {
            $todoModel = new Todo();
            $todoModel->editTask($taskId, $userId, $taskDesc, $targetDate);
        }

        header('Location: /todo');
        exit;
    }
}