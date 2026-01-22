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
    'category' => 'Fruits'
  ),
  array(
    'id' => 2,
    'name' => 'Yellow Bananas',
    'price' => 60,
    'quantity' => '6 pcs',
    'image' => 'images/banana.jpg',
    'category' => 'Fruits'
  ),
  array(
    'id' => 3,
    'name' => 'Fresh Milk',
    'price' => 65,
    'quantity' => '1 Liter',
    'image' => 'images/milk.jpg',
    'category' => 'Dairy'
  ),
  array(
    'id' => 4,
    'name' => 'Whole Wheat Bread',
    'price' => 35,
    'quantity' => '400g',
    'image' => 'images/bread.jpg',
    'category' => 'Bakery'
  ),
  array(
    'id' => 5,
    'name' => 'Basmati Rice',
    'price' => 450,
    'quantity' => '5 kg',
    'image' => 'images/rice.jpg',
    'category' => 'Staples'
  ),
  array(
    'id' => 6,
    'name' => 'Brown Eggs',
    'price' => 72,
    'quantity' => '12 pcs',
    'image' => 'images/eggs.jpg',
    'category' => 'Dairy'
  )
);

// Get first 4 products for featured section
$featured_products = array_slice($products, 0, 5);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EasyCart - Fast Grocery Delivery</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <header>
    <div class="header-container">
      <div class="logo">🛒 EasyCart</div>
      <nav>
        <ul class="nav-links">
          <li><a href="index.php" class="active">Home</a></li>
          <li><a href="products.php">Products</a></li>
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
    <!-- Hero Slider Section -->
    <div style="position: relative;">
      <input type="radio" name="slider" id="slide1" class="slider-radio" checked>
      <input type="radio" name="slider" id="slide2" class="slider-radio">
      <input type="radio" name="slider" id="slide3" class="slider-radio">

      <section class="hero">
        <div class="hero-slider">
          <div class="hero-slide active">
            <img src="images/hero1.jpg" alt="Fresh Groceries Delivery">
            <div class="hero-content">
              <h1>Fresh Groceries in 30 Minutes</h1>
              <p>Get farm-fresh produce delivered to your doorstep</p>
              <a href="products.php" class="btn btn-primary">Shop Now</a>
            </div>
          </div>

          
          </div>
        </div>

        <div class="slider-controls">
          <label for="slide1" class="slider-dot active"></label>
          <label for="slide2" class="slider-dot"></label>
          <label for="slide3" class="slider-dot"></label>
        </div>
      </section>
    </div>

    <!-- Featured Products -->
    <section class="container">
      <div class="section-title">
        <h2>Featured Products</h2>
        <p>Handpicked fresh products just for you</p>
      </div>
      
      <div class="products-grid">
        <?php foreach($featured_products as $product): ?>
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
        <?php endforeach; ?>
      </div>
    </section>

    <!-- Categories Section -->
    <section style="background-color: #f9fafb;">
      <div class="container">
        <div class="section-title">
          <h2>Shop by Category</h2>
          <p>Browse through our wide range of categories</p>
        </div>
        
        <div class="categories">
          <a href="products.php" class="category-card">
            <div class="category-icon">🥕</div>
            <h3>Fruits & Vegetables</h3>
            <p>Fresh produce daily</p>
          </a>

          <a href="products.php" class="category-card">
            <div class="category-icon">🥛</div>
            <h3>Dairy & Eggs</h3>
            <p>Premium dairy products</p>
          </a>

          <a href="products.php" class="category-card">
            <div class="category-icon">🍪</div>
            <h3>Snacks</h3>
            <p>Healthy snack options</p>
          </a>

          <a href="products.php" class="category-card">
            <div class="category-icon">🍚</div>
            <h3>Staples & Grains</h3>
            <p>Essential staples</p>
          </a>
        </div>
      </div>
    </section>

    <!-- Brands Section -->
    <section class="container">
      <div class="section-title">
        <h2>Popular Brands</h2>
        <p>Shop from trusted brands</p>
      </div>
      
      <div class="brands">
        <a href="products.php" class="brand-box">Amul Dairy</a>
        <a href="products.php" class="brand-box">Aashirvaad</a>
        <a href="products.php" class="brand-box">Haldiram's</a>
        <a href="products.php" class="brand-box">Britannia</a>
        <a href="products.php" class="brand-box">Mother Dairy</a>
        <a href="products.php" class="brand-box">Nestlé</a>
      </div>
    </section>
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
