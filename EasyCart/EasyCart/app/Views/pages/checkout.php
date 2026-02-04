  <main>
    <div class="page-container">
      <h1 class="page-title">Checkout</h1>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
        <div>
          <h2 style="font-size: 1.3rem; margin-bottom: 1.5rem;">Shipping Address</h2>

          <form id="checkout-form">
            <div class="form-group">
              <label for="full_name">Full Name</label>
              <input type="text" id="full_name" name="full_name" placeholder="Enter your full name" value="<?php echo htmlspecialchars($old['full_name'] ?? ($user_name ?? ''), ENT_QUOTES, 'UTF-8'); ?>" required>
              <?php if (!empty($field_errors['full_name'])) : ?>
                <div class="error-msg" style="color: red; font-size: 0.85rem; margin-top: 4px;">
                  <?php echo htmlspecialchars($field_errors['full_name'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
              <?php endif; ?>
            </div>

            <div class="form-group">
              <label for="phone">Phone Number</label>
              <input type="tel" id="phone" name="phone" placeholder="+91 XXXXX XXXXX" value="<?php echo htmlspecialchars($old['phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
              <?php if (!empty($field_errors['phone'])) : ?>
                <div class="error-msg" style="color: red; font-size: 0.85rem; margin-top: 4px;">
                  <?php echo htmlspecialchars($field_errors['phone'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
              <?php endif; ?>
            </div>

            <div class="form-group">
              <label for="street_address">Street Address</label>
              <input type="text" id="street_address" name="street_address" placeholder="Street address" value="<?php echo htmlspecialchars($old['street_address'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
              <?php if (!empty($field_errors['street_address'])) : ?>
                <div class="error-msg" style="color: red; font-size: 0.85rem; margin-top: 4px;">
                  <?php echo htmlspecialchars($field_errors['street_address'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
              <?php endif; ?>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
              <div class="form-group">
                <label for="city">City</label>
                <input type="text" id="city" name="city" placeholder="City" value="<?php echo htmlspecialchars($old['city'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                <?php if (!empty($field_errors['city'])) : ?>
                  <div class="error-msg" style="color: red; font-size: 0.85rem; margin-top: 4px;">
                    <?php echo htmlspecialchars($field_errors['city'], ENT_QUOTES, 'UTF-8'); ?>
                  </div>
                <?php endif; ?>
              </div>

              <div class="form-group">
                <label for="state">State</label>
                <input type="text" id="state" name="state" placeholder="State" value="<?php echo htmlspecialchars($old['state'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                <?php if (!empty($field_errors['state'])) : ?>
                  <div class="error-msg" style="color: red; font-size: 0.85rem; margin-top: 4px;">
                    <?php echo htmlspecialchars($field_errors['state'], ENT_QUOTES, 'UTF-8'); ?>
                  </div>
                <?php endif; ?>
              </div>
            </div>

            <div class="form-group">
              <label for="zip_code">ZIP Code</label>
              <input type="text" id="zip_code" name="zip_code" placeholder="ZIP code" value="<?php echo htmlspecialchars($old['zip_code'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
              <?php if (!empty($field_errors['zip_code'])) : ?>
                <div class="error-msg" style="color: red; font-size: 0.85rem; margin-top: 4px;">
                  <?php echo htmlspecialchars($field_errors['zip_code'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
              <?php endif; ?>
            </div>
          </form>

          <div class="payment-options">
            <h2 style="font-size: 1.3rem; margin-bottom: 1rem;">Payment Method</h2>
            <?php if (!empty($field_errors['payment_method'])) : ?>
              <div class="error-msg" style="color: red; font-size: 0.85rem; margin-bottom: 0.75rem;">
                <?php echo htmlspecialchars($field_errors['payment_method'], ENT_QUOTES, 'UTF-8'); ?>
              </div>
            <?php endif; ?>

            <label class="payment-option">
              <input type="radio" name="payment_method" value="card" form="checkout-form" <?php echo (($old['payment_method'] ?? '') === 'card') ? 'checked' : ''; ?> required>
              Credit / Debit Card
            </label>
            <label class="payment-option">
              <input type="radio" name="payment_method" value="upi" form="checkout-form" <?php echo (($old['payment_method'] ?? '') === 'upi') ? 'checked' : ''; ?> required>
              UPI (Google Pay, PhonePe, Paytm)
            </label>
            <label class="payment-option">
              <input type="radio" name="payment_method" value="cod" form="checkout-form" <?php echo (($old['payment_method'] ?? '') === 'cod') ? 'checked' : ''; ?> required>
              Cash on Delivery
            </label>

            <div class="payment-details hidden" data-payment="card">
              <div class="form-group">
                <label for="card_name">Name on Card</label>
                <input type="text" id="card_name" placeholder="Full name">
              </div>
              <div class="form-group">
                <label for="card_number">Card Number</label>
                <input type="text" id="card_number" placeholder="1234 5678 9012 3456">
              </div>
              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                  <label for="card_expiry">Expiry</label>
                  <input type="text" id="card_expiry" placeholder="MM/YY">
                </div>
                <div class="form-group">
                  <label for="card_cvv">CVV</label>
                  <input type="text" id="card_cvv" placeholder="123">
                </div>
              </div>
            </div>

            <div class="payment-details hidden" data-payment="upi">
              <div class="form-group">
                <label for="upi_id">UPI ID</label>
                <input type="text" id="upi_id" placeholder="name@bank">
              </div>
            </div>

            <div class="payment-details hidden" data-payment="cod">
              <p style="color: var(--text-light); margin: 0;">Pay with cash when your order is delivered.</p>
            </div>
          </div>
        </div>

        <div>
          <div class="order-summary">
            <h2 style="font-size: 1.3rem; margin-bottom: 1.5rem;">Order Summary</h2>

            <div style="background: var(--white); padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem;">
              <h3 style="font-size: 1rem; margin-bottom: 1rem;">Items in Order</h3>

              <?php
              if (count($cart_items) > 0):
                foreach ($cart_items as $item):
                  $product = \App\Models\getProductById($item['id'], $products);
                  if ($product):
                    $item_total = $product['price'] * $item['quantity'];
              ?>
              <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border-color);">
                <span><?php echo $product['name'] . ' (' . $item['quantity'] . ')'; ?></span>
                <span>₹<?php echo $item_total; ?></span>
              </div>
              <?php
                  endif;
                endforeach;
              else:
              ?>
              <p style="color: var(--text-light);">No items in your cart</p>
              <?php
              endif;
              ?>
            </div>

            <div class="summary-row">
              <span>Subtotal</span>
              <span>₹<?php echo $subtotal; ?></span>
            </div>

            <div class="summary-row">
              <span>Shipping (<?php echo $shipping_label; ?>)</span>
              <span>₹<?php echo $shipping_cost; ?></span>
            </div>

            <div class="summary-row">
              <span>Subtotal (before tax)</span>
              <span>₹<?php echo $subtotal_before_tax; ?></span>
            </div>

            <div class="summary-row gst">
              <span>GST(18%)</span>
              <span>₹<?php echo number_format($gst, 2); ?></span>
            </div>

            <div class="summary-row total">
              <span>Total Amount</span>
              <span>₹<?php echo number_format($total, 2); ?></span>
            </div>

            <div class="tax-note">
              ✓ GST included as per Indian tax regulations<br>
              ✓ Prices inclusive of applicable taxes
            </div>

            <form id="place-order-form" method="POST" action="<?php echo $base_path; ?>/orders">
              <input type="hidden" name="full_name" form="place-order-form" id="place_full_name">
              <input type="hidden" name="phone" form="place-order-form" id="place_phone">
              <input type="hidden" name="street_address" form="place-order-form" id="place_street_address">
              <input type="hidden" name="city" form="place-order-form" id="place_city">
              <input type="hidden" name="state" form="place-order-form" id="place_state">
              <input type="hidden" name="zip_code" form="place-order-form" id="place_zip_code">
              <input type="hidden" name="payment_method" form="place-order-form" id="place_payment_method">
              <button type="submit"
                      class="btn btn-primary"
                      style="width:100%;margin-top:2rem;">
                Place Order
              </button>
            </form>

            <a href="<?php echo $base_path; ?>/cart" class="btn btn-secondary" style="width: 100%; text-align: center; display: block; padding: 12px 32px; margin-top: 1rem;">
              Back to Cart
            </a>

            <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border-color); text-align: center; color: var(--text-light); font-size: 0.9rem;">
              <p>✓ Secure checkout with SSL encryption</p>
              <p>✓ Fresh items are non-returnable</p>
              <p>⏱ Delivery within 30–60 minutes</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <script>
    (function() {
      const form = document.getElementById('place-order-form');
      if (!form) return;

      const showFieldError = (id, message) => {
        const input = document.getElementById(id);
        if (!input) return;
        let error = input.parentElement.querySelector('.error-msg');
        if (!error) {
          error = document.createElement('div');
          error.className = 'error-msg';
          error.style.color = 'red';
          error.style.fontSize = '0.85rem';
          error.style.marginTop = '4px';
          input.parentElement.appendChild(error);
        }
        error.textContent = message;
        input.style.borderColor = 'red';
      };

      const clearFieldError = (id) => {
        const input = document.getElementById(id);
        if (!input) return;
        const error = input.parentElement.querySelector('.error-msg');
        if (error) error.remove();
        input.style.borderColor = '';
      };

      form.addEventListener('submit', function(e) {
        const fullName = document.getElementById('full_name')?.value.trim();
        const phone = document.getElementById('phone')?.value.trim();
        const street = document.getElementById('street_address')?.value.trim();
        const city = document.getElementById('city')?.value.trim();
        const state = document.getElementById('state')?.value.trim();
        const zip = document.getElementById('zip_code')?.value.trim();
        const pm = document.querySelector('input[name="payment_method"]:checked');

        ['full_name','phone','street_address','city','state','zip_code'].forEach(clearFieldError);

        let hasError = false;
        if (!fullName) {
          showFieldError('full_name', 'Full name is required.');
          hasError = true;
        }
        if (!phone) {
          showFieldError('phone', 'Phone number is required.');
          hasError = true;
        }
        if (!street) {
          showFieldError('street_address', 'Street address is required.');
          hasError = true;
        }
        if (!city) {
          showFieldError('city', 'City is required.');
          hasError = true;
        }
        if (!state) {
          showFieldError('state', 'State is required.');
          hasError = true;
        }
        if (!zip) {
          showFieldError('zip_code', 'ZIP code is required.');
          hasError = true;
        }
        if (!pm) {
          const pmContainer = document.querySelector('.payment-options');
          if (pmContainer && !pmContainer.querySelector('.error-msg')) {
            const error = document.createElement('div');
            error.className = 'error-msg';
            error.style.color = 'red';
            error.style.fontSize = '0.85rem';
            error.style.marginBottom = '0.75rem';
            error.textContent = 'Please select a payment method.';
            pmContainer.insertBefore(error, pmContainer.children[1]);
          }
          hasError = true;
        }

        if (hasError) {
          e.preventDefault();
          return;
        }

        document.getElementById('place_full_name').value = fullName || '';
        document.getElementById('place_phone').value = phone || '';
        document.getElementById('place_street_address').value = street || '';
        document.getElementById('place_city').value = city || '';
        document.getElementById('place_state').value = state || '';
        document.getElementById('place_zip_code').value = zip || '';
        document.getElementById('place_payment_method').value = pm ? pm.value : '';
      });
    })();
  </script>
