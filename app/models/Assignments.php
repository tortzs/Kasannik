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
}