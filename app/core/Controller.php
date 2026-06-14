<?php

class Controller
{
    protected function view(string $view, array $data = []): void
    {
        extract($data);

        $viewPath = APP_PATH . '/Views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            echo "Widok $view nie istnieje";
            return;
        }

        require_once $viewPath;
    }

    protected function model(string $model): object
    {
        $modelClass = $model;

        if (!class_exists($modelClass)) {
            echo "Model $model nie istnieje";
            exit;
        }

        return new $modelClass();
    }
}