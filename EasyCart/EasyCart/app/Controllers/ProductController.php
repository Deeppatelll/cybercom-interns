<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\ProductRepository;
use App\Models\CategoryRepository;
use App\Models\BrandRepository;
use App\Models\CartService;
use App\Models\CartRepository;

class ProductController extends Controller
{
    public function detail(): void
    {
        $cart_id = isset($_SESSION['cart_id']) ? (int)$_SESSION['cart_id'] : null;

        // Load DB cart into session if session empty
        if (empty($_SESSION['cart']) && $cart_id) {
            $_SESSION['cart'] = CartRepository::fetchCartItems($cart_id);
        }

        $cart_count = CartService::getCartCount($_SESSION['cart'] ?? []);

        $product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $product = ProductRepository::findById($product_id);

        // Handle Add To Cart
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {

            if ($product) {

                if (!isset($_SESSION['cart'])) {
                    $_SESSION['cart'] = [];
                }

                // Ensure DB cart exists
                $cart_id = CartRepository::getOrCreateCartId($cart_id);
                $_SESSION['cart_id'] = $cart_id;

                // Update session cart
                $found = false;

                foreach ($_SESSION['cart'] as &$item) {
                    if ($item['id'] == $product_id) {
                        $item['quantity'] += 1;
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    $_SESSION['cart'][] = [
                        'id' => $product_id,
                        'quantity' => 1
                    ];
                }

                // Sync to DB
                CartRepository::syncCartItems($cart_id, $_SESSION['cart']);

                // Redirect to cart
                $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
                $basePath = str_replace('/public/index.php', '', $scriptName);
                $basePath = rtrim($basePath, '/');

                header('Location: ' . $basePath . '/cart');
                exit;
            }
        }

        $this->render('product-detail', [
            'product'     => $product,
            'cart_count'  => $cart_count,
            'active_page' => 'products',
            'page_title'  => 'Product Details - EasyCart'
        ]);
    }
}

