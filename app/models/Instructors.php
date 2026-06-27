<?php

class Instructors extends Model
{
    public function getAll(): array
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM Lecturer
            WHERE UserID = :userId
        ");

        $stmt->bindValue(':userId', Auth::id(), PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
    public function getById(int $id)
    {
        $stmt = $this->pdo->prepare("Select * FROM Lecturer WHERE UserID = :userId AND ID = :id LIMIT 1");
        $stmt->bindValue(':userId', Auth::id(), PDO::PARAM_INT);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    public function instructorCheck(int $id): bool
    {
        $stmt = $this->pdo->prepare("Select * FROM Lecturer WHERE UserID = :userId AND ID = :id LIMIT 1");
        $stmt->bindValue(':userId', Auth::id(), PDO::PARAM_INT);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() === 1;
    }

    public function instructorDelete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM Lecturer WHERE UserID = :userId and ID = :ID");
        $stmt->bindValue(':userId', Auth::id(), PDO::PARAM_INT);
        $stmt->bindValue(':ID', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function instructorInsert(string $academicTitle, string $firstName, string $lastName, string $email, string $room): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO Lecturer 
    (UserID, AcademicTitle, FirstName, LastName, Email, Room) 
    VALUES (:userId, :academicTitle, :firstName, :lastName, :email, :room)");
        $stmt->bindValue(':userId', Auth::id(), PDO::PARAM_INT);
        $stmt->bindValue(':academicTitle', $academicTitle, PDO::PARAM_STR);
        $stmt->bindValue(':firstName', $firstName, PDO::PARAM_STR);
        $stmt->bindValue(':lastName', $lastName, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':room', $room, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function instructorEdit(
        int $instructorId,
        string $academicTitle,
        string $firstName,
        string $lastName,
        string $email,
        string $room
    ): bool {
        $stmt = $this->pdo->prepare("
        UPDATE Lecturer
        SET
            AcademicTitle = :academicTitle,
            FirstName = :firstName,
            LastName = :lastName,
            Email = :email,
            Room = :room
        WHERE ID = :instructorId
        AND UserID = :userId
    ");

        $stmt->bindValue(':instructorId', $instructorId, PDO::PARAM_INT);
        $stmt->bindValue(':userId', Auth::id(), PDO::PARAM_INT);
        $stmt->bindValue(':academicTitle', $academicTitle, PDO::PARAM_STR);
        $stmt->bindValue(':firstName', $firstName, PDO::PARAM_STR);
        $stmt->bindValue(':lastName', $lastName, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':room', $room, PDO::PARAM_STR);

        return $stmt->execute();
    }
}