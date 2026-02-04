<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class OrderRepository
{
    private static ?bool $hasPaymentMethodColumn = null;
    private static ?bool $hasOrderAddressNewColumns = null;
    private static ?bool $hasOrderCartIdColumn = null;
    public static function createOrderForUser(int $userId, array $summary, string $paymentMethod, ?int $cartId = null, string $status = 'Processing'): int
    {
        if (self::hasPaymentMethodColumn() || self::hasOrderCartIdColumn()) {
            $columns = ['user_id', 'subtotal', 'shipping_type', 'shipping_cost', 'tax', 'final_amount', 'created_at'];
            $values = [':user_id', ':subtotal', ':shipping_type', ':shipping_cost', ':tax', ':final_amount', 'NOW()'];

            if (self::hasPaymentMethodColumn()) {
                array_splice($columns, 6, 0, 'payment_method');
                array_splice($values, 6, 0, ':payment_method');
            }

            if (self::hasOrderCartIdColumn()) {
                array_splice($columns, 1, 0, 'cart_id');
                array_splice($values, 1, 0, ':cart_id');
            }

            $sql = 'INSERT INTO sales_order (' . implode(', ', $columns) . ')'
                . ' VALUES (' . implode(', ', $values) . ')'
                . ' RETURNING id';
            $stmt = Database::connection()->prepare($sql);
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            if (self::hasOrderCartIdColumn()) {
                $stmt->bindValue(':cart_id', $cartId, $cartId ? PDO::PARAM_INT : PDO::PARAM_NULL);
            }
            $stmt->bindValue(':subtotal', $summary['subtotal']);
            $stmt->bindValue(':shipping_type', $summary['shipping_type'] ?? 'standard');
            $stmt->bindValue(':shipping_cost', $summary['shipping_cost']);
            $stmt->bindValue(':tax', $summary['gst'] ?? 0);
            $stmt->bindValue(':final_amount', $summary['total']);
            if (self::hasPaymentMethodColumn()) {
                $stmt->bindValue(':payment_method', $paymentMethod);
            }
            $stmt->execute();

            return (int)$stmt->fetchColumn();
        }

        $sql = 'INSERT INTO sales_order (user_id, subtotal, shipping_type, shipping_cost, tax, final_amount, created_at)'
            . ' VALUES (:user_id, :subtotal, :shipping_type, :shipping_cost, :tax, :final_amount, NOW())'
            . ' RETURNING id';
        $stmt = Database::connection()->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':subtotal', $summary['subtotal']);
        $stmt->bindValue(':shipping_type', $summary['shipping_type'] ?? 'standard');
        $stmt->bindValue(':shipping_cost', $summary['shipping_cost']);
        $stmt->bindValue(':tax', $summary['gst'] ?? 0);
        $stmt->bindValue(':final_amount', $summary['total']);
        $stmt->execute();

        return (int)$stmt->fetchColumn();
    }

    private static function hasPaymentMethodColumn(): bool
    {
        if (self::$hasPaymentMethodColumn !== null) {
            return self::$hasPaymentMethodColumn;
        }

        $sql = "SELECT 1 FROM information_schema.columns WHERE table_name = 'sales_order' AND column_name = 'payment_method'";
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute();

        self::$hasPaymentMethodColumn = (bool)$stmt->fetchColumn();
        return self::$hasPaymentMethodColumn;
    }

    private static function hasOrderCartIdColumn(): bool
    {
        if (self::$hasOrderCartIdColumn !== null) {
            return self::$hasOrderCartIdColumn;
        }

        $sql = "SELECT 1 FROM information_schema.columns WHERE table_name = 'sales_order' AND column_name = 'cart_id'";
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute();

        self::$hasOrderCartIdColumn = (bool)$stmt->fetchColumn();
        return self::$hasOrderCartIdColumn;
    }
    public static function createOrder(int $cartId, array $summary, string $status = 'Processing'): int
    {
        $sql = 'INSERT INTO sales_order (user_id, subtotal, shipping_type, shipping_cost, tax, final_amount, created_at)'
            . ' VALUES (:user_id, :subtotal, :shipping_type, :shipping_cost, :tax, :final_amount, NOW())'
            . ' RETURNING id';
        $stmt = Database::connection()->prepare($sql);
        $stmt->bindValue(':user_id', null, PDO::PARAM_NULL);
        $stmt->bindValue(':subtotal', $summary['subtotal']);
        $stmt->bindValue(':shipping_type', $summary['shipping_type'] ?? 'standard');
        $stmt->bindValue(':shipping_cost', $summary['shipping_cost']);
        $stmt->bindValue(':tax', $summary['gst'] ?? 0);
        $stmt->bindValue(':final_amount', $summary['total']);
        $stmt->execute();

        return (int)$stmt->fetchColumn();
    }

    public static function saveOrderProducts(int $orderId, array $items, array $products): void
    {
        $sql = 'INSERT INTO sales_order_products (order_id, product_id, quantity, price)'
            . ' VALUES (:order_id, :product_id, :quantity, :price)';
        $stmt = Database::connection()->prepare($sql);

        foreach ($items as $item) {
            $product = getProductById((int)$item['id'], $products);
            if (!$product) {
                continue;
            }

            $qty = (int)$item['quantity'];
            $price = (float)$product['price'];

            $stmt->bindValue(':order_id', $orderId, PDO::PARAM_INT);
            $stmt->bindValue(':product_id', (int)$item['id'], PDO::PARAM_INT);
            $stmt->bindValue(':quantity', $qty, PDO::PARAM_INT);
            $stmt->bindValue(':price', $price);
            $stmt->execute();
        }
    }

    public static function saveOrderAddress(int $orderId, array $address): void
    {
        if (self::hasOrderAddressNewColumns()) {
            $sql = 'INSERT INTO order_address (order_id, full_name, phone, street_address, city, state, zip_code)'
                . ' VALUES (:order_id, :full_name, :phone, :street_address, :city, :state, :zip_code)';
            $stmt = Database::connection()->prepare($sql);
            $stmt->bindValue(':order_id', $orderId, PDO::PARAM_INT);
            $stmt->bindValue(':full_name', $address['full_name'] ?? '');
            $stmt->bindValue(':phone', $address['phone'] ?? '');
            $stmt->bindValue(':street_address', $address['street_address'] ?? '');
            $stmt->bindValue(':city', $address['city'] ?? '');
            $stmt->bindValue(':state', $address['state'] ?? '');
            $stmt->bindValue(':zip_code', $address['zip_code'] ?? '');
            $stmt->execute();
            return;
        }

        $sql = 'INSERT INTO order_address (order_id, fullname, phone, address, city, pincode)'
            . ' VALUES (:order_id, :fullname, :phone, :address, :city, :pincode)';
        $stmt = Database::connection()->prepare($sql);
        $stmt->bindValue(':order_id', $orderId, PDO::PARAM_INT);
        $stmt->bindValue(':fullname', $address['full_name'] ?? '');
        $stmt->bindValue(':phone', $address['phone'] ?? '');
        $stmt->bindValue(':address', $address['street_address'] ?? '');
        $stmt->bindValue(':city', $address['city'] ?? '');
        $stmt->bindValue(':pincode', $address['zip_code'] ?? '');
        $stmt->execute();
    }

    public static function getOrdersByUserId(int $userId): array
    {
        $sql = 'SELECT id, created_at, shipping_type, final_amount, shipping_cost, subtotal, tax'
            . ' FROM sales_order WHERE user_id = :user_id ORDER BY created_at DESC';
        $stmt = Database::connection()->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $orders = $stmt->fetchAll();
        if (empty($orders)) {
            return [];
        }

        $orderIds = array_column($orders, 'id');
        $items = self::getOrderItems($orderIds);

        $itemsByOrder = [];
        foreach ($items as $item) {
            $itemsByOrder[$item['order_id']][] = $item;
        }

        foreach ($orders as &$order) {
            $order['items'] = $itemsByOrder[$order['id']] ?? [];
        }

        return $orders;
    }

    private static function getOrderItems(array $orderIds): array
    {
        $placeholders = [];
        $params = [];
        foreach (array_values($orderIds) as $index => $id) {
            $key = ':id_' . $index;
            $placeholders[] = $key;
            $params[$key] = (int)$id;
        }

        $sql = 'SELECT sop.order_id, sop.product_id, sop.quantity, sop.price, p.name, pa.image'
            . ' FROM sales_order_products sop'
            . ' LEFT JOIN catalog_product_entity p ON p.id = sop.product_id'
            . ' LEFT JOIN catalog_product_attribute pa ON pa.product_id = sop.product_id'
            . ' WHERE sop.order_id IN (' . implode(',', $placeholders) . ')'
            . ' ORDER BY sop.order_id DESC';
        $stmt = Database::connection()->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_INT);
        }
        $stmt->execute();

        return $stmt->fetchAll();
    }

    private static function hasOrderAddressNewColumns(): bool
    {
        if (self::$hasOrderAddressNewColumns !== null) {
            return self::$hasOrderAddressNewColumns;
        }

        $sql = "SELECT 1 FROM information_schema.columns WHERE table_name = 'order_address' AND column_name = 'full_name'";
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute();

        self::$hasOrderAddressNewColumns = (bool)$stmt->fetchColumn();
        return self::$hasOrderAddressNewColumns;
    }
}
