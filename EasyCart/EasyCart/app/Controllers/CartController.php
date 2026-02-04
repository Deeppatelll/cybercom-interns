<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\CartRepository;
use App\Models\CartService;
use App\Models\ProductRepository;

class CartController extends Controller
{
    public function index(): void
    {
        $user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
        $cart_id = isset($_SESSION['cart_id']) ? (int)$_SESSION['cart_id'] : null;
        if ($user_id && empty($_SESSION['cart']) && $cart_id) {
            $_SESSION['cart'] = CartRepository::fetchCartItems($cart_id);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax']) && $_POST['ajax'] === '1') {
            $action = $_POST['action'] ?? '';

            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            $needsCartRow = in_array($action, ['add_to_cart', 'update_qty', 'delete_item', 'apply_shipping'], true);
            if ($needsCartRow) {
                $sessionId = session_id();
                $cart_id = CartRepository::findOrCreateCart($sessionId, $user_id);
                $_SESSION['cart_id'] = $cart_id;

                if ($user_id) {
                    $cart_items_db = CartRepository::getCartItems($cart_id);
                    $_SESSION['cart'] = $cart_items_db;
                }
            }

            switch ($action) {
                case 'add_to_cart':
                    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
                    $product = $product_id > 0 ? ProductRepository::findById($product_id) : null;
                    if ($product) {
                        $found = false;
                        foreach ($_SESSION['cart'] as &$item) {
                            if ($item['id'] == $product_id) {
                                $item['quantity'] += 1;
                                $found = true;
                                break;
                            }
                        }
                        if (!$found) {
                            $_SESSION['cart'][] = ['id' => $product_id, 'quantity' => 1];
                        }

                        if ($cart_id) {
                            CartRepository::addProductToCart($cart_id, $product_id, 1);
                        }
                    }
                    break;

                case 'update_qty':
                    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
                    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
                    if ($product_id > 0 && $quantity > 0) {
                        foreach ($_SESSION['cart'] as &$item) {
                            if ($item['id'] == $product_id) {
                                $item['quantity'] = $quantity;
                                break;
                            }
                        }
                    }
                    break;

                case 'delete_item':
                    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
                    if ($product_id > 0) {
                        $_SESSION['cart'] = array_values(array_filter($_SESSION['cart'], function($item) use ($product_id) {
                            return $item['id'] != $product_id;
                        }));
                    }
                    break;

                case 'apply_shipping':
                    $shipping_method = $_POST['shipping'] ?? 'standard';
                    $_SESSION['shipping'] = $shipping_method;
                    if ($cart_id) {
                        CartRepository::updateShipping($cart_id, $shipping_method);
                    }
                    break;

                case 'summary':
                default:
                    break;
            }

            if ($cart_id && in_array($action, ['update_qty', 'delete_item'], true)) {
                CartRepository::syncCartItems($cart_id, $_SESSION['cart']);
            }

            if ($user_id && $cart_id) {
                $cart_items_db = CartRepository::getCartItems($cart_id);
                $_SESSION['cart'] = $cart_items_db;
            }

            $shipping_method = $_SESSION['shipping'] ?? 'standard';
            if ($action === 'apply_shipping') {
                $shipping_method = $_POST['shipping'] ?? 'standard';
            }

            $cart_items = $_SESSION['cart'] ?? [];
            $products = ProductRepository::findByIds(array_column($cart_items, 'id'));
            $subtotal = CartService::calculateSubtotal($cart_items, $products);
            $cart_type = CartService::determineCartType($cart_items, $products, $subtotal);
            $shipping_method = CartService::normalizeShippingMethod($shipping_method, $cart_type);

            $_SESSION['cart_type'] = $cart_type;
            $_SESSION['shipping'] = $shipping_method;

            $summary = CartService::calculateCartSummary($cart_items, $products, $shipping_method);
            $_SESSION['subtotal'] = $summary['subtotal'];
            $_SESSION['shipping_cost'] = $summary['shipping_cost'];

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'cart_count' => array_sum(array_column($cart_items, 'quantity')),
                'summary' => $summary,
                'cart_type' => $cart_type,
                'shipping_method' => $shipping_method
            ]);
            exit;
        }

        if (
            $_SERVER['REQUEST_METHOD'] === 'POST'
            && !isset($_POST['apply_shipping'])
            && !isset($_POST['delete_product'])
        ) {
            $cart_items_temp = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();

            foreach ($cart_items_temp as &$item) {
                $quantity_key = 'qty_' . $item['id'];
                if (isset($_POST[$quantity_key])) {
                    $new_quantity = (int)$_POST[$quantity_key];
                    if ($new_quantity > 0) {
                        $item['quantity'] = $new_quantity;
                    }
                }
            }

            $_SESSION['cart'] = $cart_items_temp;
            if (!$cart_id && !empty($_SESSION['cart'])) {
                $sessionId = session_id();
                $cart_id = CartRepository::findOrCreateCart($sessionId, $user_id);
                $_SESSION['cart_id'] = $cart_id;
            }
            if ($cart_id) {
                CartRepository::syncCartItems($cart_id, $_SESSION['cart']);
            }
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_product'])) {
            $product_id_to_delete = (int)$_POST['delete_product'];

            if ($product_id_to_delete > 0) {
                $cart_items_temp = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();

                $_SESSION['cart'] = array_filter($cart_items_temp, function($item) use ($product_id_to_delete) {
                    return $item['id'] != $product_id_to_delete;
                });

                $_SESSION['cart'] = array_values($_SESSION['cart']);
            }

            if (!$cart_id && !empty($_SESSION['cart'])) {
                $sessionId = session_id();
                $cart_id = CartRepository::findOrCreateCart($sessionId, $user_id);
                $_SESSION['cart_id'] = $cart_id;
            }
            if ($cart_id) {
                CartRepository::syncCartItems($cart_id, $_SESSION['cart']);
            }
        }

        $cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
        $products = ProductRepository::findByIds(array_column($cart_items, 'id'));
        $subtotal = CartService::calculateSubtotal($cart_items, $products);
        $_SESSION['subtotal'] = $subtotal;

        $cart_type = CartService::determineCartType($cart_items, $products, $subtotal);
        $_SESSION['cart_type'] = $cart_type;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_shipping'])) {
            $shipping_method = $_POST['shipping'] ?? 'standard';
            if ($user_id && $cart_id) {
                CartRepository::updateShipping($cart_id, $shipping_method);
            }
        } else {
            $shipping_method = $_SESSION['shipping'] ?? 'standard';
        }

        $shipping_method = CartService::normalizeShippingMethod($shipping_method, $cart_type);
        $_SESSION['shipping'] = $shipping_method;

        if ($cart_type === 'freight') {
            $shipping_method = 'freight';
            $_SESSION['shipping'] = 'freight';
        }

        $shipping_cost = CartService::calculateShippingCost($subtotal, $shipping_method);
        $_SESSION['shipping_cost'] = $shipping_cost;

        $subtotal_before_tax = $subtotal + $shipping_cost;
        $gst = $subtotal_before_tax * 0.18;
        $total = $subtotal_before_tax + $gst;

        $cart_count = CartService::getCartCount($cart_items);

        $this->render('cart', [
            'cart_items' => $cart_items,
            'products' => $products,
            'subtotal' => $subtotal,
            'shipping_cost' => $shipping_cost,
            'subtotal_before_tax' => $subtotal_before_tax,
            'gst' => $gst,
            'total' => $total,
            'cart_type' => $cart_type,
            'shipping_method' => $shipping_method,
            'cart_count' => $cart_count,
            'active_page' => 'cart',
            'page_title' => 'Shopping Cart - EasyCart'
        ]);
    }
}
