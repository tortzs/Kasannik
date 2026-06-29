<?php

class Todo extends Model
{
    public function getTasksByUserId(int $userId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT ID, UserID, TaskDesc, TargetDate, IsCompleted 
            FROM DailyToDo 
            WHERE UserID = :userId 
            ORDER BY IsCompleted ASC, TargetDate ASC
        ");
        $stmt->execute(['userId' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addTask(int $userId, string $taskDesc, ?string $targetDate): bool
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO DailyToDo (UserID, TaskDesc, TargetDate, IsCompleted) 
            VALUES (:userId, :taskDesc, :targetDate, 0)
        ");
        return $stmt->execute([
            'userId'     => $userId,
            'taskDesc'   => $taskDesc,
            'targetDate' => $targetDate
        ]);
    }

    public function toggleTaskStatus(int $taskId, int $userId, int $status): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE DailyToDo 
            SET IsCompleted = :status 
            WHERE ID = :taskId AND UserID = :userId
        ");
        return $stmt->execute([
            'status' => $status,
            'taskId' => $taskId,
            'userId' => $userId
        ]);
    }

    public function deleteTask(int $taskId, int $userId): bool
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM DailyToDo 
            WHERE ID = :taskId AND UserID = :userId
        ");
        return $stmt->execute([
            'taskId' => $taskId,
            'userId' => $userId
        ]);
    }
}