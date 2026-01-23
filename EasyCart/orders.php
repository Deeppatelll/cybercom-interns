<?php
session_start();

// Static orders data
$orders = array(
  array(
    'id' => '#EZ-2024-001',
    'date' => 'Jan 15, 2024',
    'items' => 'Apples (2), Milk (1), Bread (2)',
    'amount' => 896,
    'status' => 'Delivered'
  ),
  array(
    'id' => '#EZ-2024-002',
    'date' => 'Jan 12, 2024',
    'items' => 'Rice (1), Oil (1), Eggs (1)',
    'amount' => 645,
    'status' => 'Delivered'
  ),
  array(
    'id' => '#EZ-2024-003',
    'date' => 'Jan 10, 2024',
    'items' => 'Onions (1), Tomatoes (1), Chips (1)',
    'amount' => 520,
    'status' => 'Delivered'
  ),
  array(
    'id' => '#EZ-2024-004',
    'date' => 'Jan 8, 2024',
    'items' => 'Bananas (2), Bread (1), Milk (1)',
    'amount' => 456,
    'status' => 'Delivered'
  ),
  array(
    'id' => '#EZ-2024-005',
    'date' => 'Jan 5, 2024',
    'items' => 'Apples (1), Rice (2), Eggs (1)',
    'amount' => 782,
    'status' => 'Delivered'
  ),
  array(
    'id' => '#EZ-2024-006',
    'date' => 'Jan 1, 2024',
    'items' => 'Milk (2), Bread (2), Oil (1)',
    'amount' => 610,
    'status' => 'Delivered'
  ),
  array(
    'id' => '#EZ-2023-101',
    'date' => 'Dec 28, 2023',
    'items' => 'Bananas (1), Tomatoes (2), Onions (2)',
    'amount' => 523,
    'status' => 'Delivered'
  ),
  array(
    'id' => '#EZ-2023-102',
    'date' => 'Dec 25, 2023',
    'items' => 'Apples (3), Milk (1), Chips (2)',
    'amount' => 889,
    'status' => 'Delivered'
  )
);
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
          <li><a href="cart.php">Cart</a></li>
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
