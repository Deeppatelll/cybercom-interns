<?php
session_start();
require_once __DIR__ . '/data/products.data.php';

$cart_count = 0;
if (!empty($_SESSION['cart'])) {
  foreach ($_SESSION['cart'] as $item) {
    $cart_count += (int)$item['quantity'];
  }
}

// Get product ID from URL parameter
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Find product using helper function
$product = getProductById($product_id, $products);

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
  <script src="assets/js/phase3.js"></script>
</head>
<body>
  <header>
    <div class="header-container">
      <div class="logo">🛒 EasyCart</div>
      <nav>
        <ul class="nav-links">
          <li><a href="index.php">Home</a></li>
          <li><a href="products.php" class="active">Products</a></li>
          <li><a href="cart.php">Cart<?php if ($cart_count > 0): ?><span class="cart-badge"><?php echo $cart_count; ?></span><?php endif; ?></a></li>
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
        <div class="product-gallery">

  <div class="product-main-image">
    <img id="mainProductImage" 
         src="<?php echo $product['image']; ?>" 
         alt="<?php echo $product['name']; ?>">
  </div>

  <div class="product-thumbnails" id="productThumbnails"></div>

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
            <form method="POST" data-add-to-cart="true">
              <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
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

