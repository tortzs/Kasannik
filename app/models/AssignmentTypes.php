<?php

class AssignmentTypes extends Model
{

    public function getAllTypes()
    {
        $stmt = $this->pdo->query("SELECT * FROM AssignmentTypes");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}