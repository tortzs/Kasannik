<?php

if (!defined('ROOT_PATH')) {
    die('Brak zdefiniowanej ścieżki ROOT_PATH.');
}

$path = ROOT_PATH . '/.env';

if (file_exists($path)) {
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        $line = trim($line);

        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }

        if (str_contains($line, '=')) {
            [$key, $value] = explode('=', $line, 2);

            $_ENV[trim($key)] = trim($value);
        }
    }
}