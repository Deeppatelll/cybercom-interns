  <main>
    <div style="position: relative;">
      <input type="radio" name="slider" id="slide1" class="slider-radio" checked>

      <section class="hero">
        <div class="hero-slider">
          <div class="hero-slide active">
            <img src="<?php echo $base_path; ?>/assets/images/hero1.jpg" alt="Fresh Groceries Delivery">
            <div class="hero-content">
              <h1>Fresh Groceries in 30 Minutes</h1>
              <p>Get farm-fresh produce delivered to your doorstep</p>
              <a href="<?php echo $base_path; ?>/products" class="btn btn-primary">Shop Now</a>
            </div>
          </div>
        </div>

        <div class="slider-controls">
          <label for="slide1" class="slider-dot active"></label>
        </div>
      </section>
    </div>

    <section class="container">
      <div class="section-title">
        <h2>Featured Products</h2>
        <p>Handpicked fresh products just for you</p>
      </div>

      <div class="products-grid">
        <?php foreach ($featured_products as $product): ?>
        <div class="product-card">
          <div class="product-image">
            <img src="<?php echo $base_path; ?>/assets/images/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
          </div>
          <div class="product-info">
            <div class="product-name"><?php echo $product['name']; ?></div>
            <div class="product-quantity"><?php echo $product['quantity']; ?></div>
            <div class="product-price">₹<?php echo $product['price']; ?></div>
            <a href="<?php echo $base_path; ?>/product?id=<?php echo $product['id']; ?>">View Details →</a>
            <div style="margin-top: 6px; font-size: 0.8rem; color: var(--text-light);">
              <?php echo ($product['shipping_type'] === 'freight') ? '✔ Freight Delivery' : '✔ Express Delivery'; ?>
            </div>
            <button class="btn btn-primary btn-small js-add-to-cart" data-product-id="<?php echo $product['id']; ?>" style="margin-top: 6px; width: 100%;">Add to Cart</button>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </section>

    <section style="background-color: #f9fafb;">
      <div class="container">
        <div class="section-title">
          <h2>Shop by Category</h2>
          <p>Browse through our wide range of categories</p>
        </div>

        <div class="categories">
          <?php
          $category_icons = array('🧺', '🥬', '🥛', '🍞', '🍚', '🍪', '🥕', '🍎');
          $icon_count = count($category_icons);
          ?>
          <?php foreach ($home_categories as $index => $category): ?>
          <a href="<?php echo $base_path; ?>/products?category=<?php echo urlencode($category); ?>" class="category-card">
            <div class="category-icon"><?php echo $category_icons[$index % $icon_count]; ?></div>
            <h3><?php echo htmlspecialchars($category); ?></h3>
            <p>Explore <?php echo htmlspecialchars($category); ?> essentials</p>
          </a>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <section class="container">
      <div class="section-title">
        <h2>Popular Brands</h2>
        <p>Shop from trusted brands</p>
      </div>

      <div class="brands">
        <?php foreach ($home_brands as $brand): ?>
        <a href="<?php echo $base_path; ?>/products?brand=<?php echo urlencode($brand); ?>" class="brand-box"><?php echo htmlspecialchars($brand); ?></a>
        <?php endforeach; ?>
      </div>
    </section>
  </main>
