<?php
class Subjects extends Semesters{

    public function getSubjectById(int $id){
        $stmt = $this->pdo->prepare("Select Subjects.* FROM Subjects 
        LEFT OUTER JOIN Semesters S on S.ID = Subjects.SemesterID
         WHERE Subjects.ID = :id
         AND S.UserID = :userId LIMIT 1");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':userId', Auth::id(), PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getSubjectsBySemester(int $semesterId): array
    {
        $stmt = $this->pdo->prepare("
        SELECT  
            Sub.ID AS SubjectID,
            Sub.Name AS SubjectName,
            Sub.ECTS AS SubjectECTS,
            Sub.SemesterID,
            Sub.InstructorID,
            Sub.MaxPossiblePoints AS SubjectMaxPossiblePoints,
            Sub.GeneralNotes AS SubjectDescription,

            L.ID AS LecturerID,
            L.FirstName AS LecturerFirstName,
            L.LastName AS LecturerLastName
        FROM Subjects Sub
        INNER JOIN Semesters S 
            ON S.ID = Sub.SemesterID
        LEFT JOIN Lecturer L 
            ON L.ID = Sub.InstructorID
        WHERE Sub.SemesterID = :semesterId 
          AND S.UserID = :userId
    ");

        $stmt->bindValue(':semesterId', $semesterId, PDO::PARAM_INT);
        $stmt->bindValue(':userId', Auth::id(), PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function subjectInsert(
        int $semesterId,
        int $instructorId,
        string $name,
        int $ects = 0,
        int $maxPossiblePoints = 100,
        string $notes = null,


    ): array {
        $userId = Auth::id();

        if ($userId === null) {
            return [
                'success' => false,
            ];
        }

        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare("
                INSERT INTO Subjects
                (SemesterID, InstructorID, Name, ECTS, MaxPossiblePoints, GeneralNotes)
                VALUES (:semesterId, :instructorId, :name, :ects, :maxPossiblePoints, :notes)
            ");
            $stmt->bindValue(':semesterId', $semesterId, PDO::PARAM_INT);
            $stmt->bindValue(':instructorId', $instructorId, PDO::PARAM_INT);
            $stmt->bindValue(':name', $name, PDO::PARAM_STR);
            $stmt->bindValue(':ects', $ects, PDO::PARAM_INT);
            $stmt->bindValue(':maxPossiblePoints', $maxPossiblePoints, PDO::PARAM_INT);
            $stmt->bindValue(':notes', $notes, PDO::PARAM_STR);


            $result = $stmt->execute();
            $subjectId = $this->pdo->lastInsertId();
            $this->pdo->commit();

            return [
                'success' => $result,
                'subjectId' => $subjectId,
            ];
        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            return false;
        }
    }


    public function subjectDelete(int $id, int $semesterId): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM Subjects WHERE SemesterID = :semesterId and ID = :ID");
        $stmt->bindValue(':semesterId', $semesterId, PDO::PARAM_INT);
        $stmt->bindValue(':ID', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function updateSubjectDetails($subjectId, $maxPoints, $description)
    {
        $stmt = $this->pdo->prepare("
            UPDATE Subjects sub
            JOIN Semesters sem ON sub.SemesterID = sem.ID
            SET sub.MaxPossiblePoints = :maxPoints, sub.GeneralNotes = :description
            WHERE sub.ID = :subjectId AND sem.UserID = :userId
        ");

        return $stmt->execute([
            'maxPoints'   => $maxPoints,
            'description' => $description,
            'subjectId'   => $subjectId,
            'userId'      => $this->userId
        ]);
    }
}