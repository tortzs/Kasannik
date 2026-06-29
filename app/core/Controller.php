<?php

class Controller
{

    protected function view(string $view, array $data = [], string $layout = 'main'): void
    {



        extract($data);

        $viewPath = APP_PATH . '/views/' . $view . '.php';
        $layoutPath = APP_PATH . '/views/layouts/' . $layout . '.php';

        if (!file_exists($viewPath)) {
            echo "Widok {$view} nie istnieje";
            return;
        }

        if (!file_exists($layoutPath)) {
            echo "Layout {$layout} nie istnieje";
            return;
        }

        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        require $layoutPath;
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