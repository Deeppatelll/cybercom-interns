<?php
session_start();
$cart_count = 0;
if (!empty($_SESSION['cart'])) {
  foreach ($_SESSION['cart'] as $item) {
    $cart_count += (int)$item['quantity'];
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - EasyCart</title>
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
          <li><a href="login.php" class="active">Login</a></li>
        </ul>
      </nav>
      <form method="GET" action="products.php" style="display: flex; align-items: center; margin-left: 2rem;">
        <input type="text" name="search" placeholder="Search products..." style="padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 4px; width: 200px;">
        <button type="submit" style="margin-left: 8px; padding: 8px 16px; background-color: var(--primary-color); color: white; border: none; border-radius: 4px; cursor: pointer;">Search</button>
      </form>
    </div>
  </header>

  <main>
    <div class="auth-container">
      <h1 class="auth-title">Login</h1>

      <form>
        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" id="email" placeholder="Enter your email" required>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" placeholder="Enter your password" required>
        </div>

        <button class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 1rem;">Login</button>
      </form>

      <div class="auth-link">
        <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
      </div>

      <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border-color); text-align: center; color: var(--text-light); font-size: 0.9rem;">
        <p>🔒 Your login is secure and encrypted</p>
        <p>By logging in, you agree to our <a href="#">Terms & Conditions</a></p>
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

