<?php

class Model
{
    protected PDO $pdo;
    protected ?int $userId = null;

    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->getConnection();
    }
    protected function getCurrentUserId(): ?int
    {
        if ($this->userId === null) {
            $this->userId = Auth::id();
        }
        return $this->userId;
    }

}