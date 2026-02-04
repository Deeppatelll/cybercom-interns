<?php

namespace App\Models;

class CartMergeService
{
    public static function sessionToUserCart(int $userId): ?int
    {
        $cartItems = $_SESSION['cart'] ?? [];

        if (empty($cartItems)) {
            return null;
        }

        $guestCartId = CartRepository::findGuestCartIdBySession(session_id());

        $cartId = CartRepository::findCartIdByUserId($userId);
        if (!$cartId) {
            $cartId = CartRepository::createCartForUser($userId);
        }

        foreach ($cartItems as $item) {
            $productId = (int)($item['id'] ?? 0);
            $quantity = (int)($item['quantity'] ?? 0);

            if ($productId > 0 && $quantity > 0) {
                CartRepository::upsertCartItem($cartId, $productId, $quantity);
            }
        }

        $_SESSION['cart_id'] = $cartId;
        unset($_SESSION['cart']);

        if ($guestCartId && $guestCartId !== $cartId) {
            CartRepository::clearCart($guestCartId);
            CartRepository::deleteCartRow($guestCartId);
        }

        return $cartId;
    }
}