  <style>
    .orders-page { position: relative; }
    .orders-page::before { content: ''; position: absolute; top: -40px; left: 0; right: 0; height: 220px; background: radial-gradient(circle at 20% 30%, rgba(16,185,129,0.12), transparent 55%), radial-gradient(circle at 80% 10%, rgba(59,130,246,0.10), transparent 50%); pointer-events: none; z-index: 0; }
    .orders-header { display: flex; align-items: center; justify-content: space-between; gap: 1rem; position: relative; z-index: 1; }
    .orders-subtitle { color: var(--text-light); margin-top: 0.35rem; }
    .orders-list { display: grid; gap: 1.25rem; position: relative; z-index: 1; }
    .order-card { background: var(--white); border: 1px solid var(--border-color); border-radius: 12px; box-shadow: var(--shadow-sm); padding: 1rem 1.25rem; }
    .order-row { display: grid; grid-template-columns: 1fr auto auto; align-items: center; gap: 1rem; }
    .order-meta { display: flex; flex-direction: column; gap: 0.25rem; }
    .order-id { font-weight: 700; font-size: 1.05rem; }
    .order-date { color: var(--text-light); font-size: 0.9rem; }
    .order-badges { display: flex; align-items: center; gap: 0.75rem; }
    .order-chip { background: var(--light-green); color: var(--dark-green); padding: 6px 12px; border-radius: 999px; font-weight: 600; font-size: 0.85rem; text-transform: capitalize; }
    .order-amount { font-weight: 700; font-size: 1.05rem; color: var(--text-dark); }
    .order-details { display: none; margin-top: 1rem; border-top: 1px dashed var(--border-color); padding-top: 1rem; gap: 1rem; }
    .order-details.open { display: grid; grid-template-columns: 1.5fr 1fr; gap: 1.5rem; }
    .order-products { display: grid; gap: 0.75rem; }
    .order-product { display: grid; grid-template-columns: 56px 1fr auto; align-items: center; gap: 0.75rem; padding: 0.5rem 0.75rem; border: 1px solid var(--border-color); border-radius: 10px; background: #f9fafb; }
    .order-product-thumb { width: 56px; height: 56px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border-color); background: var(--white); }
    .order-product-name { font-weight: 600; color: var(--text-dark); }
    .order-product-meta { color: var(--text-light); font-size: 0.85rem; }
    .order-product-price { font-weight: 600; color: var(--text-dark); }
    .order-breakdown { display: grid; grid-template-columns: 1fr auto; gap: 0.5rem 1rem; align-content: start; background: #f8fafc; border-radius: 12px; padding: 1rem; border: 1px solid var(--border-color); }
    .order-breakdown-total { font-weight: 700; }
    @media (max-width: 900px) {
      .order-row { grid-template-columns: 1fr; justify-items: start; }
      .order-details.open { grid-template-columns: 1fr; }
    }
  </style>
  <main>
    <div class="page-container orders-page">
      <div class="orders-header">
        <div>
          <h1 class="page-title">My Orders</h1>
          <p class="orders-subtitle">Track your recent purchases and view detailed breakdowns.</p>
        </div>
        <a href="<?php echo $base_path; ?>/products" class="btn btn-primary">Continue Shopping</a>
      </div>

      <?php if (empty($orders)) : ?>
        <p style="color: var(--text-light);">No orders found.</p>
      <?php else : ?>
        <div class="orders-list">
          <?php foreach ($orders as $order): ?>
            <div class="order-card">
              <div class="order-row">
                <div class="order-meta">
                  <div class="order-id">#EZ-<?php echo $order['id']; ?></div>
                  <div class="order-date"><?php echo date('M d, Y', strtotime($order['created_at'] ?? 'now')); ?></div>
                </div>
                <div class="order-badges">
                  <span class="order-chip"><?php echo htmlspecialchars($order['shipping_type'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
                  <span class="order-amount">₹<?php echo number_format((float)($order['final_amount'] ?? 0), 2); ?></span>
                </div>
                <button class="btn btn-secondary btn-small order-toggle" data-order-id="<?php echo $order['id']; ?>">View details</button>
              </div>

              <div class="order-details" id="order-details-<?php echo $order['id']; ?>">
                <div class="order-products">
                  <?php foreach (($order['items'] ?? []) as $item): ?>
                    <?php
                      $productId = (int)($item['product_id'] ?? 0);
                      $imageSrc = $item['image'] ?? '';
                      if ($imageSrc && strpos($imageSrc, 'http') !== 0 && strpos($imageSrc, '/') !== 0) {
                        $imageSrc = $base_path . '/assets/images/' . ltrim($imageSrc, '/');
                      }
                      if (!$imageSrc) {
                        $imageSrc = 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64"><rect width="100%" height="100%" fill="%23f3f4f6"/><text x="50%" y="50%" font-size="12" text-anchor="middle" fill="%239ca3af" dy=".35em">Item</text></svg>';
                      }
                    ?>
                    <a class="order-product" href="<?php echo $base_path; ?>/product?id=<?php echo $productId; ?>">
                      <img class="order-product-thumb" src="<?php echo htmlspecialchars($imageSrc, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($item['name'] ?? 'Product', ENT_QUOTES, 'UTF-8'); ?>">
                      <div class="order-product-info">
                        <div class="order-product-name"><?php echo htmlspecialchars($item['name'] ?? 'Product', ENT_QUOTES, 'UTF-8'); ?></div>
                        <div class="order-product-meta">Qty: <?php echo (int)($item['quantity'] ?? 0); ?></div>
                      </div>
                      <div class="order-product-price">₹<?php echo number_format((float)($item['price'] ?? 0), 2); ?></div>
                    </a>
                  <?php endforeach; ?>
                </div>

                <div class="order-breakdown">
                  <div>Subtotal</div>
                  <div>₹<?php echo number_format((float)($order['subtotal'] ?? 0), 2); ?></div>
                  <div>Shipping</div>
                  <div>₹<?php echo number_format((float)($order['shipping_cost'] ?? 0), 2); ?></div>
                  <div>Tax</div>
                  <div>₹<?php echo number_format((float)($order['tax'] ?? 0), 2); ?></div>
                  <div class="order-breakdown-total">Total</div>
                  <div class="order-breakdown-total">₹<?php echo number_format((float)($order['final_amount'] ?? 0), 2); ?></div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <div style="margin-top: 2rem; padding: 1.5rem; background: var(--light-gray); border-radius: 8px;">
        <h3>Need Help?</h3>
        <p>If you have any questions about your orders, please <a href="#">contact us</a> or check our <a href="#">FAQ section</a>.</p>
      </div>
    </div>
  </main>
  <script>
    (function() {
      const toggles = document.querySelectorAll('.order-toggle');
      toggles.forEach((btn) => {
        btn.addEventListener('click', () => {
          const orderId = btn.getAttribute('data-order-id');
          const detail = document.getElementById(`order-details-${orderId}`);
          if (!detail) return;
          const isOpen = detail.classList.toggle('open');
          detail.style.display = isOpen ? 'grid' : 'none';
          btn.textContent = isOpen ? 'Hide details' : 'View details';
        });
      });
    })();
  </script>
