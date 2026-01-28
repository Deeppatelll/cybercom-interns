<?php
session_start();
require_once __DIR__ . '/data/products.data.php';

$cart_count = 0;
if (!empty($_SESSION['cart'])) {
  foreach ($_SESSION['cart'] as $item) {
    $cart_count += (int)$item['quantity'];
  }
}

/* ==========================
   PHASE 4 – FINAL ORDER SAVE
========================== */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $cart_items = $_SESSION['cart'] ?? [];
    $subtotal = $_SESSION['subtotal'] ?? 0;
    $shipping_method = $_SESSION['shipping'] ?? 'standard';
    $shipping_cost = $_SESSION['shipping_cost'] ?? 40;

    $subtotal_before_tax = $subtotal + $shipping_cost;
    $gst = $subtotal_before_tax * 0.18;
    $total = $subtotal_before_tax + $gst;

    $item_strings = [];

    foreach ($cart_items as $item) {
        $product = getProductById($item['id'], $products);
        if ($product) {
            $item_strings[] = $product['name'] . ' (' . $item['quantity'] . ')';
        }
    }

    $items_text = implode(', ', $item_strings);

    $order_id = '#EZ-' . date('Ymd-His');

    $new_order = [
        'id' => $order_id,
        'date' => date('M d, Y'),
        'items' => $items_text,
        'amount' => round($total),
        'status' => 'Processing'
    ];

    if (!isset($_SESSION['orders'])) {
        $_SESSION['orders'] = [];
    }

    array_unshift($_SESSION['orders'], $new_order);

    unset($_SESSION['cart']);
    unset($_SESSION['subtotal']);
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Orders - EasyCart</title>
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
          <li><a href="cart.php">Cart<?php if ($cart_count > 0): ?><span class="cart-badge"><?php echo $cart_count; ?></span><?php endif; ?></a></li>
          <li><a href="login.php" >Login</a></li>
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
      <h1 class="page-title">My Orders</h1>
        <?php
$orders = $_SESSION['orders'] ?? [];
?>

      <table class="orders-table">
        <thead>
          <tr>
            <th>Order ID</th>
            <th>Date</th>
            <th>Items</th>
            <th>Amount</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          // Render all orders dynamically using foreach loop
          foreach($orders as $order): 
          ?>
          <tr>
            <td><?php echo $order['id']; ?></td>
            <td><?php echo $order['date']; ?></td>
            <td><?php echo $order['items']; ?></td>
            <td>₹<?php echo $order['amount']; ?></td>
            <td><span class="status-badge status-delivered"><?php echo $order['status']; ?></span></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <div style="margin-top: 2rem; padding: 1.5rem; background: var(--light-gray); border-radius: 8px;">
        <h3>Need Help?</h3>
        <p>If you have any questions about your orders, please <a href="#">contact us</a> or check our <a href="#">FAQ section</a>.</p>
      </div>

      <div style="margin-top: 2rem;">
        <a href="products.php" class="btn btn-primary">Continue Shopping</a>
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

