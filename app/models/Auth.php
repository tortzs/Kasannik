<?php

class Auth
{
    public static function id(): ?int
    {
        return isset($_SESSION['userID'])
            ? (int)$_SESSION['userID']
            : null;
    }

    public static function check(): bool
    {
        return self::id() !== null;
    }
}