<?php

namespace App\Models;

class CartService
{
    public static function getCartCount(array $cart): int
    {
        $count = 0;
        foreach ($cart as $item) {
            $count += (int)$item['quantity'];
        }
        return $count;
    }

    public static function calculateCartSummary(array $cart_items, array $products, string $shipping_method): array
    {
        $subtotal = self::calculateSubtotal($cart_items, $products);
        $shipping_cost = self::calculateShippingCost($subtotal, $shipping_method);

        $subtotal_before_tax = $subtotal + $shipping_cost;
        $gst = $subtotal_before_tax * 0.18;
        $total = $subtotal_before_tax + $gst;

        return [
            'subtotal' => $subtotal,
            'shipping_cost' => $shipping_cost,
            'subtotal_before_tax' => $subtotal_before_tax,
            'gst' => $gst,
            'total' => $total
        ];
    }

    public static function calculateSubtotal(array $cart_items, array $products): float
    {
        $subtotal = 0;
        foreach ($cart_items as $item) {
            $product = getProductById($item['id'], $products);
            if ($product) {
                $subtotal += $product['price'] * $item['quantity'];
            }
        }
        return $subtotal;
    }

    public static function determineCartType(array $cart_items, array $products, float $subtotal): string
    {
        foreach ($cart_items as $item) {
            $product = getProductById($item['id'], $products);
            if ($product && isset($product['shipping_type']) && $product['shipping_type'] === 'freight') {
                return 'freight';
            }
        }

        if ($subtotal > 300) {
            return 'freight';
        }

        return 'express';
    }

    public static function getAllowedShippingMethods(string $cart_type): array
    {
        return $cart_type === 'freight'
            ? ['white_glove', 'freight']
            : ['standard', 'express'];
    }

    public static function normalizeShippingMethod(string $shipping_method, string $cart_type): string
    {
        if ($cart_type === 'freight') {
            return 'freight';
        }

        $allowed = self::getAllowedShippingMethods($cart_type);

        if (in_array($shipping_method, $allowed, true)) {
            return $shipping_method;
        }

        return 'standard';
    }

    public static function calculateShippingCost(float $subtotal, string $shipping_method): float
    {
        switch ($shipping_method) {
            case 'express':
                return min(80, $subtotal * 0.10);
            case 'white_glove':
                return min(150, $subtotal * 0.05);
            case 'freight':
                return max(200, $subtotal * 0.03);
            case 'standard':
            default:
                return 40;
        }
    }
}
