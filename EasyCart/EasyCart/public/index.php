<?php

session_start();

require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Core/Router.php';
require_once __DIR__ . '/../app/Core/Controller.php';

require_once __DIR__ . '/../app/Models/ProductRepository.php';
require_once __DIR__ . '/../app/Models/CategoryRepository.php';
require_once __DIR__ . '/../app/Models/BrandRepository.php';
require_once __DIR__ . '/../app/Models/CartRepository.php';
require_once __DIR__ . '/../app/Models/OrderRepository.php';
require_once __DIR__ . '/../app/Models/CartService.php';
require_once __DIR__ . '/../app/Models/UserRepository.php';
require_once __DIR__ . '/../app/Models/AuthService.php';
require_once __DIR__ . '/../app/Models/CartMergeService.php';

require_once __DIR__ . '/../app/Controllers/HomeController.php';
require_once __DIR__ . '/../app/Controllers/ProductsController.php';
require_once __DIR__ . '/../app/Controllers/ProductController.php';
require_once __DIR__ . '/../app/Controllers/CartController.php';
require_once __DIR__ . '/../app/Controllers/CheckoutController.php';
require_once __DIR__ . '/../app/Controllers/OrdersController.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';

use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\ProductsController;
use App\Controllers\ProductController;
use App\Controllers\CartController;
use App\Controllers\CheckoutController;
use App\Controllers\OrdersController;
use App\Controllers\AuthController;

$router = new Router();

$router->get('/', [HomeController::class, 'index']);
$router->get('/products', [ProductsController::class, 'index']);
$router->get('/product', [ProductController::class, 'detail']);
$router->post('/product', [ProductController::class, 'detail']);
$router->get('/cart', [CartController::class, 'index']);
$router->post('/cart', [CartController::class, 'index']);
$router->get('/checkout', [CheckoutController::class, 'index']);
$router->get('/orders', [OrdersController::class, 'index']);
$router->post('/orders', [OrdersController::class, 'store']);
$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/signup', [AuthController::class, 'signup']);
$router->post('/signup', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);

$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$basePath = str_replace('/public/index.php', '', $scriptName);

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'], $basePath);
