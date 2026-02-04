  <main>
    <div class="page-container">
      <h1 class="page-title">Shopping Cart</h1>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
        <div>
          <?php if (count($cart_items) > 0): ?>
          <form method="POST">
            <table class="cart-table">
              <thead>
                <tr>
                  <th>Product</th>
                  <th>Quantity</th>
                  <th>Price</th>
                  <th>Total</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($cart_items as $item):
                  $product = \App\Models\getProductById($item['id'], $products);
                  if ($product):
                    $item_total = $product['price'] * $item['quantity'];
                ?>
                <tr data-shipping-type="<?php echo isset($product['shipping_type']) ? $product['shipping_type'] : 'express'; ?>">
                  <td><?php echo $product['name']; ?></td>
                  <td>
                    <input type="number" name="qty_<?php echo $item['id']; ?>" data-product-id="<?php echo $item['id']; ?>" value="<?php echo $item['quantity']; ?>" min="1" max="999" style="width: 60px; padding: 6px; border: 1px solid var(--border-color); border-radius: 4px;">
                  </td>
                  <td>₹<?php echo $product['price']; ?></td>
                  <td>₹<?php echo $item_total; ?></td>
                  <td>
                    <button type="submit"
                            name="delete_product"
                            value="<?php echo $item['id']; ?>"
                            data-product-id="<?php echo $item['id']; ?>"
                            class="btn-delete"
                            style="background-color: #dc2626; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 0.85rem;">
                      🗑️ Delete
                    </button>
                  </td>
                </tr>
                <?php
                  endif;
                endforeach;
                ?>
              </tbody>
            </table>
          </form>
          <?php else: ?>
          <div style="padding: 2rem; text-align: center; background: var(--light-gray); border-radius: 8px;">
            <p style="font-size: 1.1rem; color: var(--text-light);">Your cart is empty</p>
            <a href="<?php echo $base_path; ?>/products" class="btn btn-primary" style="display: inline-block; margin-top: 1rem;">Continue Shopping</a>
          </div>
          <?php endif; ?>

          <div style="margin-top: 2rem;">
            <a href="<?php echo $base_path; ?>/products" class="btn btn-secondary">Continue Shopping</a>
          </div>
        </div>

        <div>
          <div class="order-summary" data-cart-type="<?php echo $cart_type; ?>" data-shipping-method="<?php echo $shipping_method; ?>">
            <h2 style="font-size: 1.3rem; margin-bottom: 1.5rem;">Order Summary</h2>
            <form method="POST" action="<?php echo $base_path; ?>/cart">
              <h3 style="margin-bottom: 0.8rem;">Shipping Method</h3>

              <label>
                <input type="radio" name="shipping" value="standard"
                <?php if ($shipping_method == 'standard') echo 'checked'; ?>
                <?php if ($cart_type == 'freight') echo 'disabled'; ?>>
                Standard Shipping — ₹40
              </label><br>

              <label>
                <input type="radio" name="shipping" value="express"
                <?php if ($shipping_method == 'express') echo 'checked'; ?>
                <?php if ($cart_type == 'freight') echo 'disabled'; ?>>
                Express Shipping — ₹80 or 10% (whichever lower)
              </label><br>

              <label>
                <input type="radio" name="shipping" value="white_glove"
                <?php if ($shipping_method == 'white_glove') echo 'checked'; ?>
                <?php if ($cart_type == 'express') echo 'disabled'; ?>>
                White Glove — ₹150 or 5% (whichever lower)
              </label><br>

              <label>
                <input type="radio" name="shipping" value="freight"
                <?php if ($shipping_method == 'freight') echo 'checked'; ?>
                <?php if ($cart_type == 'express') echo 'disabled'; ?>>
                Freight — 3% (Min ₹200)
              </label>
              <input type="hidden" name="apply_shipping" value="1">

              <div class="summary-row">
                <span>Subtotal</span>
                <span data-summary="subtotal">₹<?php echo $subtotal; ?></span>
              </div>

              <div class="summary-row">
                <span>Shipping</span>
                <span data-summary="shipping">₹<?php echo number_format($shipping_cost, 2); ?></span>
              </div>

              <div class="summary-row">
                <span>Subtotal (before tax)</span>
                <span data-summary="subtotal_before_tax">₹<?php echo $subtotal_before_tax; ?></span>
              </div>

              <div class="summary-row gst">
                <span>GST (18%)</span>
                <span data-summary="gst">₹<?php echo number_format($gst, 2); ?></span>
              </div>

              <div class="summary-row total">
                <span>Total Payable Amount</span>
                <span data-summary="total">₹<?php echo number_format($total, 2); ?></span>
              </div>

              <div class="tax-note">
                ✓ GST included as per Indian tax regulations<br>
                ✓ Prices inclusive of applicable taxes
              </div>
            </form>
            <?php if (count($cart_items) > 0): ?>
            <a href="<?php echo $base_path; ?>/checkout" class="btn btn-primary" style="width: 100%; text-align: center; display: block; padding: 12px 32px; margin-top: 2rem;">
              Proceed to Checkout
            </a>
            <?php else: ?>
            <button class="btn btn-primary" style="width: 100%; padding: 12px 32px; margin-top: 2rem; opacity: 0.5; cursor: not-allowed;" disabled>
              Proceed to Checkout
            </button>
            <?php endif; ?>

            <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border-color); text-align: center; color: var(--text-light); font-size: 0.9rem;">
              <p>🚚 Multiple shipping options available</p>
              <p>🍃 Fresh items are non-returnable</p>
              <p>⏱ Delivery within 30–60 minutes</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
