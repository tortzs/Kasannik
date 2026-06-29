<?php

class Schedule extends Model
{
    public function getActiveSemesterSchedule(int $userId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT te.ID, te.SubjectID, te.DayOfWeek, te.StartTime, te.EndTime, 
                   te.Room, te.ClassType, te.WeekType,
                   s.Name AS SubjectName
            FROM TimetableEvents te
            JOIN Subjects s ON te.SubjectID = s.ID
            JOIN Semesters sem ON s.SemesterID = sem.ID
            WHERE sem.UserID = :userId AND sem.IsCurrent = 1
            ORDER BY te.DayOfWeek ASC, te.StartTime ASC
        ");
        $stmt->execute(['userId' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActiveSemesterInfo(int $userId): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT Name, StartDate, EndDate 
            FROM Semesters 
            WHERE UserID = :userId AND IsCurrent = 1 
            LIMIT 1
        ");
        $stmt->execute(['userId' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
    public function getActiveSemesterSubjects(int $userId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT s.ID, s.Name 
            FROM Subjects s
            JOIN Semesters sem ON s.SemesterID = sem.ID
            WHERE sem.UserID = :userId AND sem.IsCurrent = 1
            ORDER BY s.Name ASC
        ");
        $stmt->execute(['userId' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addEvent(int $subjectId, int $dayOfWeek, string $startTime, string $endTime, string $room, string $classType, string $weekType): bool
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO TimetableEvents (SubjectID, DayOfWeek, StartTime, EndTime, Room, ClassType, WeekType) 
            VALUES (:subId, :day, :start, :end, :room, :type, :week)
        ");
        return $stmt->execute([
            'subId' => $subjectId,
            'day'   => $dayOfWeek,
            'start' => $startTime,
            'end'   => $endTime,
            'room'  => $room,
            'type'  => $classType,
            'week'  => $weekType
        ]);
    }

    public function deleteEvent(int $eventId, int $userId): bool
    {
        $stmt = $this->pdo->prepare("
            DELETE te FROM TimetableEvents te
            JOIN Subjects s ON te.SubjectID = s.ID
            JOIN Semesters sem ON s.SemesterID = sem.ID
            WHERE te.ID = :eventId AND sem.UserID = :userId
        ");
        return $stmt->execute(['eventId' => $eventId, 'userId' => $userId]);
    }
}