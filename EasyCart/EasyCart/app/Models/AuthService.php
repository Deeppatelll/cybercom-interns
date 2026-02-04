<?php

namespace App\Models;

class AuthService
{
    public static function requireLogin(): void
    {
        if (!empty($_SESSION['user_id'])) {
            return;
        }

        $currentUrl = $_SERVER['REQUEST_URI'] ?? '/';
        $_SESSION['redirect_after_login'] = $currentUrl;

        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $basePath = str_replace('/public/index.php', '', $scriptName);
        $basePath = rtrim($basePath, '/');

        header('Location: ' . $basePath . '/login');
        exit;
    }
}