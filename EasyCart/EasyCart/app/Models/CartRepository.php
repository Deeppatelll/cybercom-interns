<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class CartRepository
{
    public static function findOrCreateCart(string $sessionId, ?int $userId = null): int
    {
        if ($userId) {
            $cartId = self::findCartIdByUserId($userId);
            if ($cartId) {
                return $cartId;
            }
        }

        $sql = 'SELECT id FROM sales_cart WHERE session_id = :session_id AND user_id IS NULL LIMIT 1';
        $stmt = Database::connection()->prepare($sql);
        $stmt->bindValue(':session_id', $sessionId);
        $stmt->execute();

        $cartId = $stmt->fetchColumn();
        if ($cartId) {
            return (int)$cartId;
        }

        $sql = 'INSERT INTO sales_cart (session_id, user_id, created_at)'
            . ' VALUES (:session_id, :user_id, NOW()) RETURNING id';
        $stmt = Database::connection()->prepare($sql);
        $stmt->bindValue(':session_id', $sessionId);
        $stmt->bindValue(':user_id', null, PDO::PARAM_NULL);
        $stmt->execute();

        return (int)$stmt->fetchColumn();
    }

    public static function addProductToCart(int $cartId, int $productId, int $quantity = 1): void
    {
        $sql = 'SELECT quantity FROM sales_cart_products'
            . ' WHERE cart_id = :cart_id AND product_id = :product_id LIMIT 1';
        $stmt = Database::connection()->prepare($sql);
        $stmt->bindValue(':cart_id', $cartId, PDO::PARAM_INT);
        $stmt->bindValue(':product_id', $productId, PDO::PARAM_INT);
        $stmt->execute();

        $existingQty = $stmt->fetchColumn();
        if ($existingQty !== false) {
            $update = Database::connection()->prepare(
                'UPDATE sales_cart_products SET quantity = quantity + :quantity'
                . ' WHERE cart_id = :cart_id AND product_id = :product_id'
            );
            $update->bindValue(':quantity', $quantity, PDO::PARAM_INT);
            $update->bindValue(':cart_id', $cartId, PDO::PARAM_INT);
            $update->bindValue(':product_id', $productId, PDO::PARAM_INT);
            $update->execute();
            return;
        }

        $insert = Database::connection()->prepare(
            'INSERT INTO sales_cart_products (cart_id, product_id, quantity)'
            . ' VALUES (:cart_id, :product_id, :quantity)'
        );
        $insert->bindValue(':cart_id', $cartId, PDO::PARAM_INT);
        $insert->bindValue(':product_id', $productId, PDO::PARAM_INT);
        $insert->bindValue(':quantity', $quantity, PDO::PARAM_INT);
        $insert->execute();
    }

    public static function getCartItems(int $cartId): array
    {
        $sql = 'SELECT product_id, quantity FROM sales_cart_products WHERE cart_id = :cart_id';
        $stmt = Database::connection()->prepare($sql);
        $stmt->bindValue(':cart_id', $cartId, PDO::PARAM_INT);
        $stmt->execute();

        $items = [];
        foreach ($stmt->fetchAll() as $row) {
            $items[] = [
                'id' => (int)$row['product_id'],
                'quantity' => (int)$row['quantity'],
            ];
        }

        return $items;
    }
    public static function findCartIdByUserId(int $userId): ?int
    {
        $sql = 'SELECT id FROM sales_cart WHERE user_id = :user_id ORDER BY id DESC LIMIT 1';
        $stmt = Database::connection()->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $cartId = $stmt->fetchColumn();
        return $cartId ? (int)$cartId : null;
    }

    public static function findGuestCartIdBySession(string $sessionId): ?int
    {
        $sql = 'SELECT id FROM sales_cart WHERE session_id = :session_id AND user_id IS NULL ORDER BY id DESC LIMIT 1';
        $stmt = Database::connection()->prepare($sql);
        $stmt->bindValue(':session_id', $sessionId);
        $stmt->execute();

        $cartId = $stmt->fetchColumn();
        return $cartId ? (int)$cartId : null;
    }

    public static function createCartForUser(int $userId): int
    {
        $sql = 'INSERT INTO sales_cart (session_id, user_id, created_at)'
            . ' VALUES (:session_id, :user_id, NOW()) RETURNING id';
        $stmt = Database::connection()->prepare($sql);
        $stmt->bindValue(':session_id', session_id());
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return (int)$stmt->fetchColumn();
    }

    public static function upsertCartItem(int $cartId, int $productId, int $quantity): void
    {
        $check = Database::connection()->prepare(
            'SELECT quantity FROM sales_cart_products WHERE cart_id = :cart_id AND product_id = :product_id LIMIT 1'
        );
        $check->bindValue(':cart_id', $cartId, PDO::PARAM_INT);
        $check->bindValue(':product_id', $productId, PDO::PARAM_INT);
        $check->execute();

        if ($check->fetchColumn() !== false) {
            $update = Database::connection()->prepare(
                'UPDATE sales_cart_products SET quantity = quantity + :quantity'
                . ' WHERE cart_id = :cart_id AND product_id = :product_id'
            );
            $update->bindValue(':quantity', $quantity, PDO::PARAM_INT);
            $update->bindValue(':cart_id', $cartId, PDO::PARAM_INT);
            $update->bindValue(':product_id', $productId, PDO::PARAM_INT);
            $update->execute();
            return;
        }

        $insert = Database::connection()->prepare(
            'INSERT INTO sales_cart_products (cart_id, product_id, quantity)'
            . ' VALUES (:cart_id, :product_id, :quantity)'
        );
        $insert->bindValue(':cart_id', $cartId, PDO::PARAM_INT);
        $insert->bindValue(':product_id', $productId, PDO::PARAM_INT);
        $insert->bindValue(':quantity', $quantity, PDO::PARAM_INT);
        $insert->execute();
    }
    public static function getOrCreateCartId(?int $cartId): int
    {
        if ($cartId && self::cartExists($cartId)) {
            return $cartId;
        }

        return self::createCart();
    }

    public static function cartExists(int $cartId): bool
    {
        $sql = 'SELECT 1 FROM sales_cart WHERE id = :cart_id LIMIT 1';
        $stmt = Database::connection()->prepare($sql);
        $stmt->bindValue(':cart_id', $cartId, PDO::PARAM_INT);
        $stmt->execute();

        return (bool)$stmt->fetchColumn();
    }

    public static function createCart(): int
    {
        $sql = 'INSERT INTO sales_cart (session_id, user_id, created_at)'
            . ' VALUES (:session_id, :user_id, NOW()) RETURNING id';
        $stmt = Database::connection()->prepare($sql);
        $stmt->bindValue(':session_id', session_id());
        $stmt->bindValue(':user_id', null, PDO::PARAM_NULL);
        $stmt->execute();

        return (int)$stmt->fetchColumn();
    }

    public static function fetchCartItems(int $cartId): array
    {
        $sql = 'SELECT product_id, quantity FROM sales_cart_products WHERE cart_id = :cart_id';
        $stmt = Database::connection()->prepare($sql);
        $stmt->bindValue(':cart_id', $cartId, PDO::PARAM_INT);
        $stmt->execute();

        $items = [];
        foreach ($stmt->fetchAll() as $row) {
            $items[] = [
                'id' => (int)$row['product_id'],
                'quantity' => (int)$row['quantity'],
            ];
        }

        return $items;
    }

    public static function syncCartItems(int $cartId, array $items): void
    {
        $connection = Database::connection();
        $connection->beginTransaction();

        $delete = $connection->prepare('DELETE FROM sales_cart_products WHERE cart_id = :cart_id');
        $delete->bindValue(':cart_id', $cartId, PDO::PARAM_INT);
        $delete->execute();

        if (!empty($items)) {
            $insert = $connection->prepare(
                'INSERT INTO sales_cart_products (cart_id, product_id, quantity)'
                . ' VALUES (:cart_id, :product_id, :quantity)'
            );

            foreach ($items as $item) {
                $insert->bindValue(':cart_id', $cartId, PDO::PARAM_INT);
                $insert->bindValue(':product_id', (int)$item['id'], PDO::PARAM_INT);
                $insert->bindValue(':quantity', (int)$item['quantity'], PDO::PARAM_INT);
                $insert->execute();
            }
        }

        $connection->commit();
    }

    public static function updateShipping(int $cartId, string $shippingMethod): void
    {
        $connection = Database::connection();
        $delete = $connection->prepare('DELETE FROM cart_shipping WHERE cart_id = :cart_id');
        $delete->bindValue(':cart_id', $cartId, PDO::PARAM_INT);
        $delete->execute();

        $insert = $connection->prepare(
            'INSERT INTO cart_shipping (cart_id, shipping_method, shipping_cost)'
            . ' VALUES (:cart_id, :shipping_method, :shipping_cost)'
        );
        $insert->bindValue(':cart_id', $cartId, PDO::PARAM_INT);
        $insert->bindValue(':shipping_method', $shippingMethod);
        $insert->bindValue(':shipping_cost', 0);
        $insert->execute();
    }

    public static function clearCart(int $cartId): void
    {
        $connection = Database::connection();
        $useTransaction = !$connection->inTransaction();
        if ($useTransaction) {
            $connection->beginTransaction();
        }

        $deleteItems = $connection->prepare('DELETE FROM sales_cart_products WHERE cart_id = :cart_id');
        $deleteItems->bindValue(':cart_id', $cartId, PDO::PARAM_INT);
        $deleteItems->execute();

        $deleteShipping = $connection->prepare('DELETE FROM cart_shipping WHERE cart_id = :cart_id');
        $deleteShipping->bindValue(':cart_id', $cartId, PDO::PARAM_INT);
        $deleteShipping->execute();

        if ($useTransaction) {
            $connection->commit();
        }
    }

    public static function deleteCartRow(int $cartId): void
    {
        $stmt = Database::connection()->prepare('DELETE FROM sales_cart WHERE id = :cart_id');
        $stmt->bindValue(':cart_id', $cartId, PDO::PARAM_INT);
        $stmt->execute();
    }
}
