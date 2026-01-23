<?php
session_start();
require_once __DIR__ . '/data/products.data.php';

// Handle quantity update via POST form submission
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_quantities'])) {
  $cart_items_temp = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
  
  // Update quantities from form inputs
  foreach($cart_items_temp as &$item) {
    $quantity_key = 'qty_' . $item['id'];
    if(isset($_POST[$quantity_key])) {
      $new_quantity = (int)$_POST[$quantity_key];
      if($new_quantity > 0) {
        $item['quantity'] = $new_quantity;
      }
    }
  }
  
  $_SESSION['cart'] = $cart_items_temp;
}

// Handle delete product from cart via POST
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_product'])) {
  $product_id_to_delete = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
  
  if($product_id_to_delete > 0) {
    $cart_items_temp = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
    
    // Remove the product from cart
    $_SESSION['cart'] = array_filter($cart_items_temp, function($item) use ($product_id_to_delete) {
      return $item['id'] != $product_id_to_delete;
    });
    
    // Re-index array to maintain consistency
    $_SESSION['cart'] = array_values($_SESSION['cart']);
  }
}

// Calculate cart totals
$subtotal = 0;
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();

if(count($cart_items) > 0) {
  foreach($cart_items as $item) {
    $product = getProductById($item['id'], $products);
    if($product) {
      $subtotal += $product['price'] * $item['quantity'];
    }
  }
}

// Store subtotal in session for checkout page
$_SESSION['subtotal'] = $subtotal;

$delivery_charge = 49;
$subtotal_before_tax = $subtotal + $delivery_charge;
$gst = $subtotal_before_tax * 0.05;
$total = $subtotal_before_tax + $gst;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shopping Cart - EasyCart</title>
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
      <h1 class="page-title">Shopping Cart</h1>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
        <div>
          <?php if(count($cart_items) > 0): ?>
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
                foreach($cart_items as $item):
                  $product = getProductById($item['id'], $products);
                  if($product):
                    $item_total = $product['price'] * $item['quantity'];
                ?>
                <tr>
                  <td><?php echo $product['name']; ?></td>
                  <td>
                    <input type="number" name="qty_<?php echo $item['id']; ?>" value="<?php echo $item['quantity']; ?>" min="1" max="999" style="width: 60px; padding: 6px; border: 1px solid var(--border-color); border-radius: 4px;">
                  </td>
                  <td>₹<?php echo $product['price']; ?></td>
                  <td>₹<?php echo $item_total; ?></td>
                  <td>
                    <form method="POST" style="display: inline;">
                      <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                      <button type="submit" name="delete_product" class="btn-delete" style="background-color: #dc2626; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 0.85rem;">
                        🗑️ Delete
                      </button>
                    </form>
                  </td>
                </tr>
                <?php 
                  endif;
                endforeach; 
                ?>
              </tbody>
            </table>
           
          <?php else: ?>
          <div style="padding: 2rem; text-align: center; background: var(--light-gray); border-radius: 8px;">
            <p style="font-size: 1.1rem; color: var(--text-light);">Your cart is empty</p>
            <a href="products.php" class="btn btn-primary" style="display: inline-block; margin-top: 1rem;">Continue Shopping</a>
          </div>
          <?php endif; ?>
          
          <div style="margin-top: 2rem;">
            <a href="products.php" class="btn btn-secondary">Continue Shopping</a>
          </div>
        </div>

        <div>
          <div class="order-summary">
            <h2 style="font-size: 1.3rem; margin-bottom: 1.5rem;">Order Summary</h2>
            
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
              <span>Total Payable Amount</span>
              <span>₹<?php echo number_format($total, 2); ?></span>
            </div>

            <div class="tax-note">
              ✓ GST included as per Indian tax regulations<br>
              ✓ Prices inclusive of applicable taxes
            </div>

            <?php if(count($cart_items) > 0): ?>
            <a href="checkout.php" class="btn btn-primary" style="width: 100%; text-align: center; display: block; padding: 12px 32px; margin-top: 2rem;">
              Proceed to Checkout
            </a>
            <?php else: ?>
            <button class="btn btn-primary" style="width: 100%; padding: 12px 32px; margin-top: 2rem; opacity: 0.5; cursor: not-allowed;" disabled>
              Proceed to Checkout
            </button>
            <?php endif; ?>

            <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border-color); text-align: center; color: var(--text-light); font-size: 0.9rem;">
              <p>🚚 Free delivery on orders above ₹500</p>
              <p>🍃 Fresh items are non-returnable</p>
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
  <script src="assets/js/phase3.js"></script>
</body>
</html>

