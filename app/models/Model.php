<?php

class Model
{
    protected PDO $pdo;
    protected ?int $userId;

    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->getConnection();
        $this->userId = Auth::id();

    }
}