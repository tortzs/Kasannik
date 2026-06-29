<?php

class Semesters extends Model
{
    public function getAll(): array
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM Semesters
            WHERE UserID = :userId
        ");

        $stmt->bindValue(':userId', Auth::id(), PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
    public function getById(int $id)
    {
        $stmt = $this->pdo->prepare("Select * FROM Semesters WHERE UserID = :userId AND ID = :id LIMIT 1");
        $stmt->bindValue(':userId', Auth::id(), PDO::PARAM_INT);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    public function getCurrent()
    {
        $stmt = $this->pdo->prepare("Select * FROM Semesters WHERE UserID = :userId AND IsCurrent = :isCurrent LIMIT 1");
        $stmt->bindValue(':userId', Auth::id(), PDO::PARAM_INT);
        $stmt->bindValue(':isCurrent', 1, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    public function semesterCheck(int $id): bool
    {
        $stmt = $this->pdo->prepare("Select * FROM Semesters WHERE UserID = :userId AND ID = :id LIMIT 1");
        $stmt->bindValue(':userId', Auth::id(), PDO::PARAM_INT);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() === 1;
    }

    public function semesterDelete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM Semesters WHERE UserID = :userId and ID = :ID");
        $stmt->bindValue(':userId', Auth::id(), PDO::PARAM_INT);
        $stmt->bindValue(':ID', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function semesterInsert(
        string $name,
        string $startDate,
        string $endDate
    ): bool {
        $userId = Auth::id();

        if ($userId === null) {
            return false;
        }

        try {
            $this->pdo->beginTransaction();


            $stmt = $this->pdo->prepare("
                INSERT INTO Semesters
                (UserID, Name, StartDate, EndDate)
                VALUES (:userId, :name, :startDate, :endDate)
            ");

            $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindValue(':name', $name, PDO::PARAM_STR);
            $stmt->bindValue(':startDate', $startDate, PDO::PARAM_STR);
            $stmt->bindValue(':endDate', $endDate, PDO::PARAM_STR);

            $result = $stmt->execute();

            $this->pdo->commit();

            return $result;
        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            return false;
        }
    }

    public function semesterEdit(
        string $semesterId,
        string $name,
        string $startDate,
        string $endDate
    ): bool {
        $stmt = $this->pdo->prepare("
        UPDATE Semesters
        SET
            Name = :name,
            StartDate = :startDate,
            EndDate = :endDate
        WHERE ID = :semesterId
        AND UserID = :userId
    ");


        $stmt->bindValue(':userId', Auth::id(), PDO::PARAM_INT);
        $stmt->bindValue(':semesterId', $semesterId, PDO::PARAM_STR);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':startDate', $startDate, PDO::PARAM_STR);
        $stmt->bindValue(':endDate', $endDate, PDO::PARAM_STR);
        return $stmt->execute();
    }
    public function getActiveSemesterId(int $userId): ?int
    {
        $stmt = $this->pdo->prepare("
            SELECT ID 
            FROM Semesters 
            WHERE UserID = :userId AND IsCurrent = 1 
            LIMIT 1
        ");

        $stmt->execute(['userId' => $userId]);
        $result = $stmt->fetchColumn();

        return $result ? (int)$result : null;
    }
}