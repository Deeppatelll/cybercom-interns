<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\CartRepository;
use App\Models\CartService;
use App\Models\ProductRepository;
use App\Models\AuthService;

class CheckoutController extends Controller
{
    public function index(): void
    {
        if (!empty($_SESSION['checkout_pending']) && empty($_SESSION['user_id'])) {
            AuthService::requireLogin();
        }

        $cart_id = isset($_SESSION['cart_id']) ? (int)$_SESSION['cart_id'] : null;
        if (empty($_SESSION['cart']) && $cart_id) {
            $_SESSION['cart'] = CartRepository::fetchCartItems($cart_id);
        }

        $cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
        $products = ProductRepository::findByIds(array_column($cart_items, 'id'));
        $subtotal = $_SESSION['subtotal'] ?? 0;

        $shipping_method = $_SESSION['shipping'] ?? 'standard';
        $shipping_cost = $_SESSION['shipping_cost'] ?? 40;

        $shipping_labels = [
            'standard' => 'Standard Shipping',
            'express' => 'Express Shipping',
            'white_glove' => 'White Glove Delivery',
            'freight' => 'Freight Shipping'
        ];

        $shipping_label = $shipping_labels[$shipping_method] ?? ucfirst($shipping_method);

        $subtotal_before_tax = $subtotal + $shipping_cost;
        $gst = $subtotal_before_tax * 0.18;
        $total = $subtotal_before_tax + $gst;

        $cart_count = CartService::getCartCount($cart_items);

        $user_name = $_SESSION['user_name'] ?? '';
        $user_email = $_SESSION['user_email'] ?? '';

        $this->render('checkout', [
            'products' => $products,
            'cart_items' => $cart_items,
            'subtotal' => $subtotal,
            'shipping_method' => $shipping_method,
            'shipping_cost' => $shipping_cost,
            'shipping_label' => $shipping_label,
            'subtotal_before_tax' => $subtotal_before_tax,
            'gst' => $gst,
            'total' => $total,
            'cart_count' => $cart_count,
            'user_name' => $user_name,
            'user_email' => $user_email,
            'active_page' => 'cart',
            'page_title' => 'Checkout - EasyCart'
        ]);
    }
}
