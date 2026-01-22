<?php
session_start();

// Static product data
$products = array(
  array(
    'id' => 1,
    'name' => 'Fresh Apples',
    'price' => 120,
    'quantity' => '1 kg',
    'image' => 'images/apple.jpg',
    'category' => 'Fruits',
    'description' => 'These fresh, crispy apples are handpicked from premium orchards. Loaded with natural vitamins and fiber, they\'re perfect for a healthy breakfast or as a snack. Rich in antioxidants, these apples help boost your immune system and are great for overall wellness.'
  ),
  array(
    'id' => 2,
    'name' => 'Yellow Bananas',
    'price' => 60,
    'quantity' => '6 pcs',
    'image' => 'images/banana.jpg',
    'category' => 'Fruits',
    'description' => 'Ripe and sweet yellow bananas packed with potassium and natural energy. Perfect for snacking or smoothies.'
  ),
  array(
    'id' => 3,
    'name' => 'Fresh Milk',
    'price' => 65,
    'quantity' => '1 Liter',
    'image' => 'images/milk.jpg',
    'category' => 'Dairy',
    'description' => 'Pure, fresh dairy milk delivered daily. Rich in calcium and proteins for your family\'s health.'
  ),
  array(
    'id' => 4,
    'name' => 'Whole Wheat Bread',
    'price' => 35,
    'quantity' => '400g',
    'image' => 'images/bread.jpg',
    'category' => 'Bakery',
    'description' => 'Fresh whole wheat bread baked with natural ingredients. High in fiber and nutrients.'
  ),
  array(
    'id' => 5,
    'name' => 'Basmati Rice',
    'price' => 450,
    'quantity' => '5 kg',
    'image' => 'images/rice.jpg',
    'category' => 'Staples',
    'description' => 'Premium quality basmati rice with perfect grain separation and aroma.'
  ),
  array(
    'id' => 6,
    'name' => 'Cooking Oil',
    'price' => 210,
    'quantity' => '1 Liter',
    'image' => 'images/oil.jpg',
    'category' => 'Staples',
    'description' => 'Pure, refined cooking oil perfect for everyday cooking needs.'
  ),
  array(
    'id' => 7,
    'name' => 'Brown Eggs',
    'price' => 72,
    'quantity' => '12 pcs',
    'image' => 'images/eggs.jpg',
    'category' => 'Dairy',
    'description' => 'Fresh brown eggs from farm. Rich in nutrients and protein.'
  ),
  array(
    'id' => 8,
    'name' => 'Fresh Onions',
    'price' => 40,
    'quantity' => '1 kg',
    'image' => 'images/onion.jpg',
    'category' => 'Vegetables',
    'description' => 'Fresh, crispy onions ideal for all your culinary needs.'
  ),
  array(
    'id' => 9,
    'name' => 'Ripe Tomatoes',
    'price' => 50,
    'quantity' => '1 kg',
    'image' => 'images/tomato.jpg',
    'category' => 'Vegetables',
    'description' => 'Farm-fresh ripe tomatoes perfect for cooking or salads.'
  ),
  array(
    'id' => 10,
    'name' => 'Potato Chips',
    'price' => 45,
    'quantity' => '200g',
    'image' => 'images/chips.jpg',
    'category' => 'Snacks',
    'description' => 'Crispy potato chips, a perfect snack for any time.'
  )
);

// Get product ID from URL parameter
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Find product in array
$product = null;
foreach($products as $p) {
  if($p['id'] == $product_id) {
    $product = $p;
    break;
  }
}

// Handle "Add to Cart" request
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
  if($product) {
    // Initialize cart session array if not exists
    if(!isset($_SESSION['cart'])) {
      $_SESSION['cart'] = array();
    }
    
    // Check if product already in cart
    $found = false;
    foreach($_SESSION['cart'] as &$item) {
      if($item['id'] == $product_id) {
        $item['quantity'] += 1;
        $found = true;
        break;
      }
    }
    
    // Add new product to cart if not found
    if(!$found) {
      $_SESSION['cart'][] = array(
        'id' => $product_id,
        'quantity' => 1
      );
    }
    
    // Redirect to cart page
    header("Location: cart.php");
    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Product Details - EasyCart</title>
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
        <input type="text" name="search" placeholder="Search products..." style="padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 4px; width: 200px;">
        <button type="submit" style="margin-left: 8px; padding: 8px 16px; background-color: var(--primary-color); color: white; border: none; border-radius: 4px; cursor: pointer;">Search</button>
      </form>
    </div>
  </header>

  <main>
    <div class="container">
      <?php if($product): ?>
      <div class="product-detail-container">
        <div class="product-detail-image">
          <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
        </div>

        <div class="product-detail-info">
          <h1><?php echo $product['name']; ?></h1>
          
          <div class="detail-quantity-info">
            Available in: <strong><?php echo $product['quantity']; ?></strong>
          </div>

          <div class="detail-price">₹<?php echo $product['price']; ?></div>

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
            <form method="POST">
              <button type="submit" name="add_to_cart" class="btn btn-primary">Add to Cart</button>
            </form>
            <a href="products.php" class="btn btn-secondary">Back to Products</a>
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
        <a href="products.php" class="btn btn-primary" style="margin-top: 1rem; display: inline-block;">Back to Products</a>
      </div>
      <?php endif; ?>
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
