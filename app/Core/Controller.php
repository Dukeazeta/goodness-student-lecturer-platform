<?php

namespace App\Core;

class Controller
{
    protected function render(string $view, array $data = [], string $layout = 'layout'): void
    {
        extract($data);
        $viewPath = dirname(__DIR__) . '/Views/' . $view . '.php';
        if (!is_file($viewPath)) {
            http_response_code(500);
            exit('View not found: ' . $view);
        }

        $layoutPath = dirname(__DIR__) . '/Views/' . $layout . '.php';
        if (!is_file($layoutPath)) {
            http_response_code(500);
            exit('Layout not found: ' . $layout);
        }

        require $layoutPath;
    }
}
