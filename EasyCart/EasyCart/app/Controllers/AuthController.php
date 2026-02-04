<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\CartMergeService;
use App\Models\CartService;
use App\Models\UserRepository;

class AuthController extends Controller
{
    public function login(): void
    {
        if (!empty($_SESSION['user_id'])) {
            if (!empty($_SESSION['redirect_after_login'])) {
                $redirectUrl = $_SESSION['redirect_after_login'];
                unset($_SESSION['redirect_after_login']);
                header('Location: ' . $redirectUrl);
                exit;
            }

            $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
            $basePath = str_replace('/public/index.php', '', $scriptName);
            $basePath = rtrim($basePath, '/');

            header('Location: ' . $basePath . '/');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $errors = [];

            if ($email === '') {
                $errors[] = 'Email is required.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Please enter a valid email address.';
            }

            if ($password === '') {
                $errors[] = 'Password is required.';
            }

            $user = null;
            if (empty($errors)) {
                $user = UserRepository::findByEmail($email);
                if (!$user || !password_verify($password, $user['password'] ?? '')) {
                    $errors[] = 'Invalid email or password.';
                }
            }

            if (!empty($errors)) {
                $cart_count = CartService::getCartCount($_SESSION['cart'] ?? []);
                $this->render('login', [
                    'cart_count' => $cart_count,
                    'active_page' => 'login',
                    'page_title' => 'Login - EasyCart',
                    'errors' => $errors,
                    'old' => [
                        'email' => $email
                    ]
                ]);
                return;
            }

            session_regenerate_id(true);
            $_SESSION['user_id'] = (int)($user['id'] ?? 0);
            $_SESSION['user_name'] = $user['name'] ?? '';
            $_SESSION['user_email'] = $user['email'] ?? '';

            if (!empty($_SESSION['checkout_pending'])) {
                CartMergeService::sessionToUserCart($_SESSION['user_id']);
                unset($_SESSION['checkout_pending']);
                unset($_SESSION['redirect_after_login']);

                $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
                $basePath = str_replace('/public/index.php', '', $scriptName);
                $basePath = rtrim($basePath, '/');

                header('Location: ' . $basePath . '/checkout');
                exit;
            }

            if (!empty($_SESSION['redirect_after_login'])) {
                $redirectUrl = $_SESSION['redirect_after_login'];
                unset($_SESSION['redirect_after_login']);

                header('Location: ' . $redirectUrl);
                exit;
            }

            $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
            $basePath = str_replace('/public/index.php', '', $scriptName);
            $basePath = rtrim($basePath, '/');

            header('Location: ' . $basePath . '/');
            exit;
        }

        $cart_count = CartService::getCartCount($_SESSION['cart'] ?? []);

        $this->render('login', [
            'cart_count' => $cart_count,
            'active_page' => 'login',
            'page_title' => 'Login - EasyCart',
            'errors' => [],
            'old' => [
                'email' => ''
            ]
        ]);
    }

    public function signup(): void
    {
        if (!empty($_SESSION['user_id'])) {
            if (!empty($_SESSION['redirect_after_login'])) {
                $redirectUrl = $_SESSION['redirect_after_login'];
                unset($_SESSION['redirect_after_login']);
                header('Location: ' . $redirectUrl);
                exit;
            }

            $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
            $basePath = str_replace('/public/index.php', '', $scriptName);
            $basePath = rtrim($basePath, '/');

            header('Location: ' . $basePath . '/');
            exit;
        }

        $cart_count = CartService::getCartCount($_SESSION['cart'] ?? []);

        $this->render('signup', [
            'cart_count' => $cart_count,
            'active_page' => 'login',
            'page_title' => 'Sign Up - EasyCart',
            'errors' => [],
            'old' => [
                'name' => '',
                'email' => ''
            ]
        ]);
    }

    public function register(): void
    {
        if (!empty($_SESSION['user_id'])) {
            if (!empty($_SESSION['redirect_after_login'])) {
                $redirectUrl = $_SESSION['redirect_after_login'];
                unset($_SESSION['redirect_after_login']);
                header('Location: ' . $redirectUrl);
                exit;
            }

            $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
            $basePath = str_replace('/public/index.php', '', $scriptName);
            $basePath = rtrim($basePath, '/');

            header('Location: ' . $basePath . '/');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        $errors = [];

        if ($name === '') {
            $errors[] = 'Full name is required.';
        }

        if ($email === '') {
            $errors[] = 'Email is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
        }

        if ($password === '') {
            $errors[] = 'Password is required.';
        } elseif (strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters.';
        }

        if ($confirmPassword === '') {
            $errors[] = 'Please confirm your password.';
        } elseif ($password !== $confirmPassword) {
            $errors[] = 'Passwords do not match.';
        }

        if (empty($errors)) {
            $existing = UserRepository::findByEmail($email);
            if ($existing) {
                $errors[] = 'Email is already registered.';
            }
        }

        if (!empty($errors)) {
            $cart_count = CartService::getCartCount($_SESSION['cart'] ?? []);
            $this->render('signup', [
                'cart_count' => $cart_count,
                'active_page' => 'login',
                'page_title' => 'Sign Up - EasyCart',
                'errors' => $errors,
                'old' => [
                    'name' => $name,
                    'email' => $email
                ]
            ]);
            return;
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $userId = UserRepository::insertUser($name, $email, $passwordHash);

        session_regenerate_id(true);
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;

        if (!empty($_SESSION['checkout_pending'])) {
            CartMergeService::sessionToUserCart($_SESSION['user_id']);
            unset($_SESSION['checkout_pending']);
            unset($_SESSION['redirect_after_login']);

            $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
            $basePath = str_replace('/public/index.php', '', $scriptName);
            $basePath = rtrim($basePath, '/');

            header('Location: ' . $basePath . '/checkout');
            exit;
        }

        if (!empty($_SESSION['redirect_after_login'])) {
            $redirectUrl = $_SESSION['redirect_after_login'];
            unset($_SESSION['redirect_after_login']);

            header('Location: ' . $redirectUrl);
            exit;
        }

        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $basePath = str_replace('/public/index.php', '', $scriptName);
        $basePath = rtrim($basePath, '/');

        header('Location: ' . $basePath . '/');
        exit;
    }

    public function logout(): void
    {
        $_SESSION = [];
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }

        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $basePath = str_replace('/public/index.php', '', $scriptName);
        $basePath = rtrim($basePath, '/');

        header('Location: ' . $basePath . '/');
        exit;
    }
}
