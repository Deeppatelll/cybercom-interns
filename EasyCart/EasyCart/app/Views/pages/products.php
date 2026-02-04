  <main>
    <div class="page-container">
      <h1 class="page-title">All Products</h1>

      <?php if (!empty($search_keyword)): ?>
      <div style="background-color: #f0f9ff; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; border-left: 4px solid var(--primary-color);">
        <p style="margin: 0; color: var(--text-dark);">Search results for: <strong><?php echo htmlspecialchars($search_keyword); ?></strong></p>
      </div>
      <?php endif; ?>

      <div class="products-container">
        <aside class="filters-sidebar">
          <div class="filters-header">
            <h3>Filters</h3>
            <?php if (!empty($selected_categories) || !empty($selected_brands) || !empty($selected_price) || !empty($selected_units)): ?>
            <a href="<?php echo $base_path; ?>/products<?php echo !empty($search_keyword) ? '?search=' . urlencode($search_keyword) : ''; ?>" class="clear-filters-link">Clear All</a>
            <?php endif; ?>
          </div>

          <form method="GET" action="<?php echo $base_path; ?>/products" class="filters-form">
            <?php if (!empty($search_keyword)): ?>
            <input type="hidden" name="search" value="<?php echo htmlspecialchars($search_keyword); ?>">
            <?php endif; ?>

            <div class="filter-group">
              <h4 class="filter-title">Category</h4>
              <div class="filter-options">
                <?php foreach ($all_categories as $category): ?>
                <label class="filter-checkbox">
                  <input type="checkbox" name="category[]" value="<?php echo htmlspecialchars($category); ?>" <?php echo in_array($category, $selected_categories) ? 'checked' : ''; ?>>
                  <span><?php echo htmlspecialchars($category); ?></span>
                </label>
                <?php endforeach; ?>
              </div>
            </div>

            <div class="filter-group">
              <h4 class="filter-title">Brand</h4>
              <div class="filter-options">
                <?php foreach ($all_brands as $brand): ?>
                <label class="filter-checkbox">
                  <input type="checkbox" name="brand[]" value="<?php echo htmlspecialchars($brand); ?>" <?php echo in_array($brand, $selected_brands) ? 'checked' : ''; ?>>
                  <span><?php echo htmlspecialchars($brand); ?></span>
                </label>
                <?php endforeach; ?>
              </div>
            </div>

            <div class="filter-group">
              <h4 class="filter-title">Price Range</h4>
              <div class="filter-options">
                <label class="filter-radio">
                  <input type="radio" name="price" value="" <?php echo empty($selected_price) ? 'checked' : ''; ?>>
                  <span>All Prices</span>
                </label>
                <label class="filter-radio">
                  <input type="radio" name="price" value="under_100" <?php echo $selected_price === 'under_100' ? 'checked' : ''; ?>>
                  <span>Under ₹100</span>
                </label>
                <label class="filter-radio">
                  <input type="radio" name="price" value="100_300" <?php echo $selected_price === '100_300' ? 'checked' : ''; ?>>
                  <span>₹100 - ₹300</span>
                </label>
                <label class="filter-radio">
                  <input type="radio" name="price" value="above_300" <?php echo $selected_price === 'above_300' ? 'checked' : ''; ?>>
                  <span>Above ₹300</span>
                </label>
              </div>
            </div>

            <div class="filter-group">
              <h4 class="filter-title">Unit Type</h4>
              <div class="filter-options">
                <?php foreach ($all_units as $unit): ?>
                <label class="filter-checkbox">
                  <input type="checkbox" name="unit[]" value="<?php echo htmlspecialchars($unit); ?>" <?php echo in_array($unit, $selected_units) ? 'checked' : ''; ?>>
                  <span><?php echo htmlspecialchars(ucfirst($unit)); ?></span>
                </label>
                <?php endforeach; ?>
              </div>
            </div>

            <button type="submit" class="btn-apply-filters">Apply Filters</button>
          </form>
        </aside>

        <div class="products-grid">
        <?php
        if (count($filtered_products) > 0):
          foreach ($paginated_products as $product):
        ?>
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
        <?php
          endforeach;
        else:
        ?>
        <div style="grid-column: 1 / -1; padding: 3rem; text-align: center; background: var(--light-gray); border-radius: 8px;">
          <p style="font-size: 1.1rem; color: var(--text-light); margin: 0;">No products found</p>
          <p style="color: var(--text-light); margin-top: 0.5rem;">Try searching for something else or <a href="<?php echo $base_path; ?>/products" style="color: var(--primary-color); text-decoration: none;">view all products</a></p>
        </div>
        <?php endif; ?>
      </div>
        </div>
    </div>
   <?php if ($total_pages > 1): ?>
<div class="pagination">

<?php
$query = $_GET;

$range = 2;

$start = max(1, $page - $range);
$end   = min($total_pages, $page + $range);

if ($start > 1) {
    $query['page'] = 1;
    echo '<a class="page-link" href="' . $base_path . '/products?' .
          http_build_query($query) . '">1</a>';

    if ($start > 2) {
        echo '<span class="page-dots">...</span>';
    }
}

for ($i = $start; $i <= $end; $i++) {

    $query['page'] = $i;
    $active = $i == $page ? 'active' : '';

    echo '<a class="page-link ' . $active . '" href="' . $base_path . '/products?' .
          http_build_query($query) . '">' . $i . '</a>';
}

if ($end < $total_pages) {

    if ($end < $total_pages - 1) {
        echo '<span class="page-dots">...</span>';
    }

    $query['page'] = $total_pages;
    echo '<a class="page-link" href="' . $base_path . '/products?' .
          http_build_query($query) . '">' . $total_pages . '</a>';
}
?>

</div>
<?php endif; ?>
  </main>
