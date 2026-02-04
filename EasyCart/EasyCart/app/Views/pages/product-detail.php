  <main>
    <div class="container">
      <?php if ($product): ?>
      <div class="product-detail-container">
        <div class="product-gallery">
          <div class="product-main-image">
            <img id="mainProductImage" src="<?php echo $base_path; ?>/assets/images/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
          </div>

          <div class="product-thumbnails" id="productThumbnails"></div>
        </div>

        <div class="product-detail-info">
          <h1><?php echo $product['name']; ?></h1>

          <div class="detail-quantity-info">
            Available in: <strong><?php echo $product['quantity']; ?></strong>
          </div>

          <div class="detail-price">₹<?php echo $product['price']; ?></div>

          <div style="margin-top: 10px; margin-bottom: 15px; font-size: 0.95rem; color: #16a34a; font-weight: 500;">
            <?php echo ($product['shipping_type'] === 'freight') ? '✔ Freight Delivery' : '✔ Express Delivery'; ?>
          </div>

          <div class="detail-description">
            <p><?php echo $product['description']; ?></p>

            <p><strong>Benefits:</strong></p>
            <ul style="margin-left: 20px;">
              <li>Rich in vitamin C</li>
              <li>Good source of dietary fiber</li>
              <li>Contains polyphenols (antioxidants)</li>
              <li>Low in calories</li>
            </ul>
          </div>

          <div class="detail-actions">
            <form method="POST" action="<?php echo $base_path; ?>/product?id=<?php echo $product['id']; ?>" data-add-to-cart="true">
              <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
              <button type="submit" name="add_to_cart" class="btn btn-primary">Add to Cart</button>
            </form>
            <a href="<?php echo $base_path; ?>/products" class="btn btn-secondary">Back to Products</a>
          </div>

          <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #e5e7eb;">
            <h3 style="font-size: 1.1rem;">Product Information</h3>

            <div style="margin-top: 1rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
              <div>
                <p><strong>Freshness Guarantee</strong></p>
                <p style="color: var(--text-light); font-size: 0.95rem;">Picked within 24 hours of delivery</p>
              </div>

              <div>
                <p><strong>Best Before</strong></p>
                <p style="color: var(--text-light); font-size: 0.95rem;">5-7 days from delivery date</p>
              </div>

              <div>
                <p><strong>Storage Instructions</strong></p>
                <p style="color: var(--text-light); font-size: 0.95rem;">Keep at room temperature or refrigerate for extended freshness</p>
              </div>

              <div>
                <p><strong>Source</strong></p>
                <p style="color: var(--text-light); font-size: 0.95rem;">Local farm - Himachal Pradesh</p>
              </div>

              <div>
                <p><strong>Delivery</strong></p>
                <p style="color: var(--text-light); font-size: 0.95rem;">Same-day delivery available</p>
              </div>

              <div>
                <p><strong>Availability</strong></p>
                <p style="color: var(--text-light); font-size: 0.95rem;">In Stock - Ships Immediately</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php else: ?>
      <div style="text-align: center; padding: 3rem;">
        <h2>Product Not Found</h2>
        <p style="color: var(--text-light); margin-top: 1rem;">The product you're looking for does not exist.</p>
        <a href="<?php echo $base_path; ?>/products" class="btn btn-primary" style="margin-top: 1rem; display: inline-block;">Back to Products</a>
      </div>
      <?php endif; ?>
    </div>
  </main>
