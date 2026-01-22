<?php
session_start();

// Static product data
$products = array(
  array('id' => 1, 'name' => 'Fresh Apples', 'price' => 120, 'quantity' => '1 kg', 'image' => 'images/apple.jpg', 'category' => 'Fruits'),
  array('id' => 2, 'name' => 'Yellow Bananas', 'price' => 60, 'quantity' => '6 pcs', 'image' => 'images/banana.jpg', 'category' => 'Fruits'),
  array('id' => 3, 'name' => 'Fresh Milk', 'price' => 65, 'quantity' => '1 Liter', 'image' => 'images/milk.jpg', 'category' => 'Dairy'),
  array('id' => 4, 'name' => 'Whole Wheat Bread', 'price' => 35, 'quantity' => '400g', 'image' => 'images/bread.jpg', 'category' => 'Bakery'),
  array('id' => 5, 'name' => 'Basmati Rice', 'price' => 450, 'quantity' => '5 kg', 'image' => 'images/rice.jpg', 'category' => 'Staples'),
  array('id' => 6, 'name' => 'Cooking Oil', 'price' => 210, 'quantity' => '1 Liter', 'image' => 'images/oil.jpg', 'category' => 'Staples'),
  array('id' => 7, 'name' => 'Brown Eggs', 'price' => 72, 'quantity' => '12 pcs', 'image' => 'images/eggs.jpg', 'category' => 'Dairy'),
  array('id' => 8, 'name' => 'Fresh Onions', 'price' => 40, 'quantity' => '1 kg', 'image' => 'images/onion.jpg', 'category' => 'Vegetables'),
  array('id' => 9, 'name' => 'Ripe Tomatoes', 'price' => 50, 'quantity' => '1 kg', 'image' => 'images/tomato.jpg', 'category' => 'Vegetables'),
  array('id' => 10, 'name' => 'Potato Chips', 'price' => 45, 'quantity' => '200g', 'image' => 'images/chips.jpg', 'category' => 'Snacks')
);

// Function to find product by ID
function getProductById($id, $products) {
  foreach($products as $product) {
    if($product['id'] == $id) {
      return $product;
    }
  }
  return null;
}

// Get cart data and calculate totals
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
$subtotal = isset($_SESSION['subtotal']) ? $_SESSION['subtotal'] : 0;

// Handle delivery option selection via POST form
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['select_delivery'])) {
  $selected_delivery = isset($_POST['delivery']) ? $_POST['delivery'] : 'express';
  
  // Set delivery charge based on selection
  if($selected_delivery == 'standard') {
    $delivery_charge = 29;
  } elseif($selected_delivery == 'scheduled') {
    $delivery_charge = 0;
  } else {
    $delivery_charge = 49; // express (default)
  }
  
  // Store in session
  $_SESSION['delivery_charge'] = $delivery_charge;
  $_SESSION['delivery_option'] = $selected_delivery;
} else {
  // Get stored delivery charge from session or use default
  $delivery_charge = isset($_SESSION['delivery_charge']) ? $_SESSION['delivery_charge'] : 49;
}

// Get selected delivery option for radio button state
$selected_delivery = isset($_SESSION['delivery_option']) ? $_SESSION['delivery_option'] : 'express';

// Calculate order totals
$subtotal_before_tax = $subtotal + $delivery_charge;
$gst = $subtotal_before_tax * 0.05;
$total = $subtotal_before_tax + $gst;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout - EasyCart</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <header>
    <div class="header-container">
      <div class="logo">🛒 EasyCart</div>
      <nav>
        <ul class="nav-links">
          <li><a href="index.php">Home</a></li>
          <li><a href="products.php">Products</a></li>
          <li><a href="cart.php" class="active">Cart</a></li>
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
    <div class="page-container">
      <h1 class="page-title">Checkout</h1>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
        <div>
          <h2 style="font-size: 1.3rem; margin-bottom: 1.5rem;">Shipping Address</h2>
          
          <form>
            <div class="form-group">
              <label for="fullname">Full Name</label>
              <input type="text" id="fullname" placeholder="Enter your full name" value="Rahul Kumar">
            </div>

            <div class="form-group">
              <label for="email">Email Address</label>
              <input type="email" id="email" placeholder="your@email.com" value="rahul@example.com">
            </div>

            <div class="form-group">
              <label for="phone">Phone Number</label>
              <input type="tel" id="phone" placeholder="+91 XXXXX XXXXX" value="+91 98765 43210">
            </div>

            <div class="form-group">
              <label for="address">Address</label>
              <input type="text" id="address" placeholder="Street address" value="123 Main Street, Apt 4B">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
              <div class="form-group">
                <label for="city">City</label>
                <input type="text" id="city" placeholder="City" value="New Delhi">
              </div>

              <div class="form-group">
                <label for="zip">ZIP Code</label>
                <input type="text" id="zip" placeholder="ZIP code" value="110001">
              </div>
            </div>
          </form>

          <h2 style="font-size: 1.3rem; margin-top: 2.5rem; margin-bottom: 1.5rem;">Delivery Option</h2>
          
          <form method="POST">
            <div class="form-group">
              <label style="display: flex; align-items: center; margin-bottom: 1rem;">
                <input type="radio" name="delivery" value="express" <?php echo ($selected_delivery === 'express') ? 'checked' : ''; ?>>
                <span style="margin-left: 0.5rem;">Express Delivery (30 min) - ₹49</span>
              </label>
            </div>

            <div class="form-group">
              <label style="display: flex; align-items: center; margin-bottom: 1rem;">
                <input type="radio" name="delivery" value="standard" <?php echo ($selected_delivery === 'standard') ? 'checked' : ''; ?>>
                <span style="margin-left: 0.5rem;">Standard Delivery (1-2 hours) - ₹29</span>
              </label>
            </div>

            <div class="form-group">
              <label style="display: flex; align-items: center; margin-bottom: 1rem;">
                <input type="radio" name="delivery" value="scheduled" <?php echo ($selected_delivery === 'scheduled') ? 'checked' : ''; ?>>
                <span style="margin-left: 0.5rem;">Scheduled Delivery (Next day) - Free</span>
              </label>
            </div>

            <button type="submit" name="select_delivery" class="btn btn-primary" style="margin-top: 1rem;">Update Delivery Option</button>
          </form>
        </div>

        <div>
          <div class="order-summary">
            <h2 style="font-size: 1.3rem; margin-bottom: 1.5rem;">Order Summary</h2>
            
            <div style="background: var(--white); padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem;">
              <h3 style="font-size: 1rem; margin-bottom: 1rem;">Items in Order</h3>
              
              <?php 
              if(count($cart_items) > 0):
                foreach($cart_items as $item):
                  $product = getProductById($item['id'], $products);
                  if($product):
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
              <span>Delivery Charges</span>
              <span>₹<?php echo $delivery_charge; ?></span>
            </div>

            <div class="summary-row">
              <span>Subtotal (before tax)</span>
              <span>₹<?php echo $subtotal_before_tax; ?></span>
            </div>

            <div class="summary-row gst">
              <span>GST (5%)</span>
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

            <a href="orders.php" class="btn btn-primary" style="width: 100%; text-align: center; display: block; padding: 12px 32px; margin-top: 2rem;">
              Place Order
            </a>

            <a href="cart.php" class="btn btn-secondary" style="width: 100%; text-align: center; display: block; padding: 12px 32px; margin-top: 1rem;">
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

