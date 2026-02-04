<?php

namespace App\Core;

class Controller
{
    protected function render(string $view, array $data = []): void
    {
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $basePath = str_replace('/public/index.php', '', $scriptName);
        $basePath = rtrim($basePath, '/');

        if (!array_key_exists('base_path', $data)) {
            $data['base_path'] = $basePath;
        }

        $viewPath = __DIR__ . '/../Views/pages/' . $view . '.php';
        $layoutPath = __DIR__ . '/../Views/layouts/main.php';

        extract($data);

        if (file_exists($layoutPath)) {
            require $layoutPath;
            return;
        }

        require $viewPath;
    }
}
