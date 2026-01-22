<?php
session_start();

// Static product data - all products
$products = array(
  array(
    'id' => 1,
    'name' => 'Fresh Apples',
    'price' => 120,
    'quantity' => '1 kg',
    'image' => 'images/apple.jpg',
    'category' => 'Fruits',
    'brand' => 'Green Valley',
    'unit' => 'kg',
    'weight_value' => 1
  ),
  array(
    'id' => 2,
    'name' => 'Yellow Bananas',
    'price' => 60,
    'quantity' => '6 pcs',
    'image' => 'images/banana.jpg',
    'category' => 'Fruits',
    'brand' => 'Fresh Harvest',
    'unit' => 'pcs',
    'weight_value' => 6
  ),
  array(
    'id' => 3,
    'name' => 'Fresh Milk',
    'price' => 65,
    'quantity' => '1 Liter',
    'image' => 'images/milk.jpg',
    'category' => 'Dairy',
    'brand' => 'Pure Dairy',
    'unit' => 'liter',
    'weight_value' => 1
  ),
  array(
    'id' => 4,
    'name' => 'Whole Wheat Bread',
    'price' => 35,
    'quantity' => '400g',
    'image' => 'images/bread.jpg',
    'category' => 'Bakery',
    'brand' => 'Baker\'s Pride',
    'unit' => 'pcs',
    'weight_value' => 1
  ),
  array(
    'id' => 5,
    'name' => 'Basmati Rice',
    'price' => 450,
    'quantity' => '5 kg',
    'image' => 'images/rice.jpg',
    'category' => 'Staples',
    'brand' => 'Rajesh',
    'unit' => 'kg',
    'weight_value' => 5
  ),
  array(
    'id' => 6,
    'name' => 'Cooking Oil',
    'price' => 210,
    'quantity' => '1 Liter',
    'image' => 'images/oil.jpg',
    'category' => 'Staples',
    'brand' => 'Gold Standard',
    'unit' => 'liter',
    'weight_value' => 1
  ),
  array(
    'id' => 7,
    'name' => 'Brown Eggs',
    'price' => 72,
    'quantity' => '12 pcs',
    'image' => 'images/eggs.jpg',
    'category' => 'Dairy',
    'brand' => 'Farm Fresh',
    'unit' => 'pcs',
    'weight_value' => 12
  ),
  array(
    'id' => 8,
    'name' => 'Fresh Onions',
    'price' => 40,
    'quantity' => '1 kg',
    'image' => 'images/onion.jpg',
    'category' => 'Vegetables',
    'brand' => 'Green Valley',
    'unit' => 'kg',
    'weight_value' => 1
  ),
  array(
    'id' => 9,
    'name' => 'Ripe Tomatoes',
    'price' => 50,
    'quantity' => '1 kg',
    'image' => 'images/tomato.jpg',
    'category' => 'Vegetables',
    'brand' => 'Fresh Harvest',
    'unit' => 'kg',
    'weight_value' => 1
  ),
  array(
    'id' => 10,
    'name' => 'Potato Chips',
    'price' => 45,
    'quantity' => '200g',
    'image' => 'images/chips.jpg',
    'category' => 'Snacks',
    'brand' => 'Crispy Bites',
    'unit' => 'pcs',
    'weight_value' => 1
  )
);

// Handle search functionality
$search_keyword = isset($_GET['search']) ? trim($_GET['search']) : '';

// Extract unique filter options from products
$all_categories = array_unique(array_column($products, 'category'));
$all_brands = array_unique(array_column($products, 'brand'));
$all_units = array_unique(array_column($products, 'unit'));

// Get filter values from GET parameters
$selected_categories = isset($_GET['category']) ? (is_array($_GET['category']) ? $_GET['category'] : array($_GET['category'])) : array();
$selected_brands = isset($_GET['brand']) ? (is_array($_GET['brand']) ? $_GET['brand'] : array($_GET['brand'])) : array();
$selected_price = isset($_GET['price']) ? $_GET['price'] : '';
$selected_units = isset($_GET['unit']) ? (is_array($_GET['unit']) ? $_GET['unit'] : array($_GET['unit'])) : array();

// Filter products based on all criteria
$filtered_products = array_filter($products, function($product) use ($search_keyword, $selected_categories, $selected_brands, $selected_price, $selected_units) {
  // Search filter
  if (!empty($search_keyword) && stripos($product['name'], $search_keyword) === false) {
    return false;
  }
  
  // Category filter
  if (!empty($selected_categories) && !in_array($product['category'], $selected_categories)) {
    return false;
  }
  
  // Brand filter
  if (!empty($selected_brands) && !in_array($product['brand'], $selected_brands)) {
    return false;
  }
  
  // Price filter
  if (!empty($selected_price)) {
    switch($selected_price) {
      case 'under_100':
        if ($product['price'] >= 100) return false;
        break;
      case '100_300':
        if ($product['price'] < 100 || $product['price'] > 300) return false;
        break;
      case 'above_300':
        if ($product['price'] <= 300) return false;
        break;
    }
  }
  
  // Unit filter
  if (!empty($selected_units) && !in_array($product['unit'], $selected_units)) {
    return false;
  }
  
  return true;
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Products - EasyCart</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <header>
    <div class="header-container">
      <div class="logo">🛒 EasyCart</div>
      <nav>
        <ul class="nav-links">
          <li><a href="index.php">Home</a></li>
          <li><a href="products.php" class="active">Products</a></li>
          <li><a href="cart.php">Cart</a></li>
          <li><a href="login.php">Login</a></li>
        </ul>
      </nav>
      <form method="GET" action="products.php" style="display: flex; align-items: center; margin-left: 2rem;">
        <input type="text" name="search" placeholder="Search products..." style="padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 4px; width: 200px;" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button type="submit" style="margin-left: 8px; padding: 8px 16px; background-color: var(--primary-color); color: white; border: none; border-radius: 4px; cursor: pointer;">Search</button>
      </form>
    </div>
  </header>

  <main>
    <div class="page-container">
      <h1 class="page-title">All Products</h1>
      
      <?php if(!empty($search_keyword)): ?>
      <div style="background-color: #f0f9ff; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; border-left: 4px solid var(--primary-color);">
        <p style="margin: 0; color: var(--text-dark);">Search results for: <strong><?php echo htmlspecialchars($search_keyword); ?></strong></p>
      </div>
      <?php endif; ?>
      
      <div class="products-container">
        <!-- Vertical Filters Sidebar -->
        <aside class="filters-sidebar">
          <div class="filters-header">
            <h3>Filters</h3>
            <?php if(!empty($selected_categories) || !empty($selected_brands) || !empty($selected_price) || !empty($selected_units)): ?>
            <a href="products.php<?php echo !empty($search_keyword) ? '?search=' . urlencode($search_keyword) : ''; ?>" class="clear-filters-link">Clear All</a>
            <?php endif; ?>
          </div>

          <form method="GET" action="products.php" class="filters-form">
            <!-- Keep search keyword in form -->
            <?php if(!empty($search_keyword)): ?>
            <input type="hidden" name="search" value="<?php echo htmlspecialchars($search_keyword); ?>">
            <?php endif; ?>

            <!-- Category Filter -->
            <div class="filter-group">
              <h4 class="filter-title">Category</h4>
              <div class="filter-options">
                <?php foreach($all_categories as $category): ?>
                <label class="filter-checkbox">
                  <input type="checkbox" name="category[]" value="<?php echo htmlspecialchars($category); ?>" <?php echo in_array($category, $selected_categories) ? 'checked' : ''; ?>>
                  <span><?php echo htmlspecialchars($category); ?></span>
                </label>
                <?php endforeach; ?>
              </div>
            </div>

            <!-- Brand Filter -->
            <div class="filter-group">
              <h4 class="filter-title">Brand</h4>
              <div class="filter-options">
                <?php foreach($all_brands as $brand): ?>
                <label class="filter-checkbox">
                  <input type="checkbox" name="brand[]" value="<?php echo htmlspecialchars($brand); ?>" <?php echo in_array($brand, $selected_brands) ? 'checked' : ''; ?>>
                  <span><?php echo htmlspecialchars($brand); ?></span>
                </label>
                <?php endforeach; ?>
              </div>
            </div>

            <!-- Price Filter -->
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

            <!-- Unit Filter -->
            <div class="filter-group">
              <h4 class="filter-title">Unit Type</h4>
              <div class="filter-options">
                <?php foreach($all_units as $unit): ?>
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

        <!-- Products Grid -->
        <div class="products-grid">
        <?php 
        // Render filtered products dynamically
        if(count($filtered_products) > 0):
          foreach($filtered_products as $product): 
        ?>
        <div class="product-card">
          <div class="product-image">
            <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
          </div>
          <div class="product-info">
            <div class="product-name"><?php echo $product['name']; ?></div>
            <div class="product-quantity"><?php echo $product['quantity']; ?></div>
            <div class="product-price">₹<?php echo $product['price']; ?></div>
            <a href="product-detail.php?id=<?php echo $product['id']; ?>">View Details →</a>
          </div>
        </div>
        <?php 
          endforeach; 
        else:
        ?>
        <div style="grid-column: 1 / -1; padding: 3rem; text-align: center; background: var(--light-gray); border-radius: 8px;">
          <p style="font-size: 1.1rem; color: var(--text-light); margin: 0;">No products found</p>
          <p style="color: var(--text-light); margin-top: 0.5rem;">Try searching for something else or <a href="products.php" style="color: var(--primary-color); text-decoration: none;">view all products</a></p>
        </div>
        <?php endif; ?>
      </div>
        </div>
    </div>
  </main>

  <footer>
    <div class="container">
      <div class="footer-content">
        <div class="footer-section">
          <h4>About Us</h4>
          <a href="#">About EasyCart</a>
          <a href="#">Our Mission</a>
          <a href="#">Blog</a>
        </div>

        <div class="footer-section">
          <h4>Help</h4>
          <a href="#">FAQ</a>
          <a href="#">Contact Us</a>
          <a href="#">Track Order</a>
        </div>

        <div class="footer-section">
          <h4>Policy</h4>
          <a href="#">Privacy Policy</a>
          <a href="#">Terms & Conditions</a>
          <a href="#">Refund Policy</a>
        </div>

        <div class="footer-section">
          <h4>Follow Us</h4>
          <a href="#">Facebook</a>
          <a href="#">Instagram</a>
          <a href="#">Twitter</a>
        </div>
      </div>

      <div class="footer-bottom">
        <p>&copy; 2024 EasyCart. All rights reserved. Grocery Delivery Made Easy.</p>
      </div>
    </div>
  </footer>
</body>
</html>
