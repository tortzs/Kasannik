<?php

function envValue(string $key, mixed $default = null): mixed
{
    static $env = null;

    if ($env === null) {
        $env = [];

        $path = ROOT_PATH . '/.env';

        if (file_exists($path)) {
            $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            foreach ($lines as $line) {
                if (str_starts_with(trim($line), '#')) {
                    continue;
                }

                [$name, $value] = explode('=', $line, 2);

                $env[trim($name)] = trim($value);
            }
        }
    }

    return $env[$key] ?? $default;
}


class Database
{
    private PDO $pdo;

    public function __construct()
    {
        $host = envValue('DB_HOST', '127.0.0.1');
        $port = envValue('DB_PORT', '3306');
        $dbname = envValue('DB_NAME');
        $username = envValue('DB_USER');
        $password = envValue('DB_PASS');

        $this->pdo = new PDO(
            "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
            $username,
            $password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}