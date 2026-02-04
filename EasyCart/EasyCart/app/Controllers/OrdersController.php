<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Models\CartRepository;
use App\Models\CartService;
use App\Models\OrderRepository;
use App\Models\ProductRepository;
use App\Models\AuthService;
use App\Models\CartMergeService;
use function App\Models\getProductById;

class OrdersController extends Controller
{
    public function index(): void
    {
        if (empty($_SESSION['user_id'])) {
            AuthService::requireLogin();
        }

        $cart_count = CartService::getCartCount($_SESSION['cart'] ?? []);
        $orders = OrderRepository::getOrdersByUserId((int)$_SESSION['user_id']);

        $this->render('orders', [
            'orders' => $orders,
            'cart_count' => $cart_count,
            'active_page' => 'orders',
            'page_title' => 'My Orders - EasyCart'
        ]);
    }

    public function store(): void
    {
        if (empty($_SESSION['user_id'])) {
            $_SESSION['checkout_pending'] = true;
            AuthService::requireLogin();
        }

        $cart_id = isset($_SESSION['cart_id']) ? (int)$_SESSION['cart_id'] : null;
        if (!empty($_SESSION['cart'])) {
            CartMergeService::sessionToUserCart((int)$_SESSION['user_id']);
            $cart_id = isset($_SESSION['cart_id']) ? (int)$_SESSION['cart_id'] : $cart_id;
        }
        if (!$cart_id) {
            $cart_id = CartRepository::findCartIdByUserId((int)$_SESSION['user_id']);
            if ($cart_id) {
                $_SESSION['cart_id'] = $cart_id;
            }
        }
        if (empty($_SESSION['cart']) && $cart_id) {
            $_SESSION['cart'] = CartRepository::fetchCartItems($cart_id);
        }

        $cart_items = $_SESSION['cart'] ?? [];
        if (empty($cart_items) && $cart_id) {
            $cart_items = CartRepository::fetchCartItems($cart_id);
            $_SESSION['cart'] = $cart_items;
        }
        $products = ProductRepository::findByIds(array_column($cart_items, 'id'));
        $subtotal = $_SESSION['subtotal'] ?? 0;
        $shipping_method = $_SESSION['shipping'] ?? 'standard';
        $shipping_cost = $_SESSION['shipping_cost'] ?? 40;

        $subtotal_before_tax = $subtotal + $shipping_cost;
        $gst = $subtotal_before_tax * 0.18;
        $total = $subtotal_before_tax + $gst;

        $payment_method = trim($_POST['payment_method'] ?? '');
        $address = [
            'full_name' => trim($_POST['full_name'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'street_address' => trim($_POST['street_address'] ?? ''),
            'city' => trim($_POST['city'] ?? ''),
            'state' => trim($_POST['state'] ?? ''),
            'zip_code' => trim($_POST['zip_code'] ?? ''),
        ];

        $field_errors = [];
        if ($address['full_name'] === '') {
            $field_errors['full_name'] = 'Full name is required.';
        }
        if ($address['phone'] === '') {
            $field_errors['phone'] = 'Phone number is required.';
        }
        if ($address['street_address'] === '') {
            $field_errors['street_address'] = 'Street address is required.';
        }
        if ($address['city'] === '') {
            $field_errors['city'] = 'City is required.';
        }
        if ($address['state'] === '') {
            $field_errors['state'] = 'State is required.';
        }
        if ($address['zip_code'] === '') {
            $field_errors['zip_code'] = 'ZIP code is required.';
        }
        if ($payment_method === '') {
            $field_errors['payment_method'] = 'Please select a payment method.';
        }

        if (!empty($field_errors)) {
            $shipping_labels = [
                'standard' => 'Standard Shipping',
                'express' => 'Express Shipping',
                'white_glove' => 'White Glove Delivery',
                'freight' => 'Freight Shipping'
            ];
            $shipping_label = $shipping_labels[$shipping_method] ?? ucfirst($shipping_method);

            $cart_count = CartService::getCartCount($cart_items);

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
                'user_name' => $_SESSION['user_name'] ?? '',
                'user_email' => $_SESSION['user_email'] ?? '',
                'field_errors' => $field_errors,
                'old' => [
                    'full_name' => $address['full_name'],
                    'phone' => $address['phone'],
                    'street_address' => $address['street_address'],
                    'city' => $address['city'],
                    'state' => $address['state'],
                    'zip_code' => $address['zip_code'],
                    'payment_method' => $payment_method,
                ],
                'active_page' => 'cart',
                'page_title' => 'Checkout - EasyCart'
            ]);
            return;
        }

        $item_strings = [];

        foreach ($cart_items as $item) {
            $product = getProductById($item['id'], $products);
            if ($product) {
                $item_strings[] = $product['name'] . ' (' . $item['quantity'] . ')';
            }
        }

        $items_text = implode(', ', $item_strings);

        $order_id = '#EZ-' . date('Ymd-His');

        if ($cart_id) {
            $summary = [
                'subtotal' => $subtotal,
                'shipping_cost' => $shipping_cost,
                'gst' => $gst,
                'total' => $total,
                'shipping_type' => $shipping_method,
            ];

            $connection = Database::connection();
            $connection->beginTransaction();
            try {
                $db_order_id = OrderRepository::createOrderForUser((int)$_SESSION['user_id'], $summary, $payment_method, $cart_id, 'Processing');
                OrderRepository::saveOrderProducts($db_order_id, $cart_items, $products);
                OrderRepository::saveOrderAddress($db_order_id, $address);

                CartRepository::clearCart($cart_id);
                CartRepository::deleteCartRow($cart_id);

                $guestCartId = CartRepository::findGuestCartIdBySession(session_id());
                if ($guestCartId && $guestCartId !== $cart_id) {
                    CartRepository::clearCart($guestCartId);
                    CartRepository::deleteCartRow($guestCartId);
                }

                $connection->commit();
            } catch (\Throwable $e) {
                $connection->rollBack();
                throw $e;
            }
        }

        unset($_SESSION['cart']);
        unset($_SESSION['cart_id']);
        unset($_SESSION['subtotal']);
        unset($_SESSION['shipping']);
        unset($_SESSION['shipping_cost']);
        unset($_SESSION['cart_type']);

        $orders = OrderRepository::getOrdersByUserId((int)$_SESSION['user_id']);
        $cart_count = CartService::getCartCount($_SESSION['cart'] ?? []);

        $this->render('orders', [
            'orders' => $orders,
            'cart_count' => $cart_count,
            'active_page' => 'orders',
            'page_title' => 'My Orders - EasyCart'
        ]);
    }
}
