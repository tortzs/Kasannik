<?php

class Assignments extends Model
{
    public function getAssignmentsBySubject($subjectId)
    {
        $stmt = $this->pdo->prepare("
            SELECT a.*, at.TypeName 
            FROM Assignments a
            JOIN AssignmentTypes at ON a.TypeID = at.TypeID
            JOIN Subjects sub ON a.SubjectID = sub.ID
            JOIN Semesters sem ON sub.SemesterID = sem.ID
            WHERE a.SubjectID = :subjectId AND sem.UserID = :userId
        ");

        $stmt->execute([
            'subjectId' => $subjectId,
            'userId' => $this->userId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertAssignment($subjectId, $typeId, $title, $maxPoints, $deadline)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO Assignments (SubjectID, TypeID, Title, MaxPoints, Deadline, IsCompleted)
            VALUES (:subjectId, :typeId, :title, :maxPoints, :deadline, 0)
        ");

        $stmt->execute([
            'subjectId' => $subjectId,
            'typeId'    => $typeId,
            'title'     => $title,
            'maxPoints' => $maxPoints,
            'deadline'  => $deadline
        ]);

        return $this->pdo->lastInsertId();
    }
    public function deleteAssignment($id)
    {
        $stmt = $this->pdo->prepare("DELETE a
            FROM Assignments a
            JOIN Subjects sub ON a.SubjectID = sub.ID
            JOIN Semesters sem ON sub.SemesterID = sem.ID
            WHERE a.ID = :id AND sem.UserID = :userId;");
        return $stmt->execute(['id' => $id, 'userId' => $this->userId]);
    }
}