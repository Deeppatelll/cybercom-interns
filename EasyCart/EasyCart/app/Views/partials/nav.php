<nav>
  <ul class="nav-links">
    <li><a href="<?php echo $base_path; ?>/" class="<?php echo ($active_page ?? '') === 'home' ? 'active' : ''; ?>">Home</a></li>
    <li><a href="<?php echo $base_path; ?>/products" class="<?php echo ($active_page ?? '') === 'products' ? 'active' : ''; ?>">Products</a></li>
    <li><a href="<?php echo $base_path; ?>/cart" class="<?php echo ($active_page ?? '') === 'cart' ? 'active' : ''; ?>">Cart<?php if (!empty($cart_count)): ?><span class="cart-badge"><?php echo $cart_count; ?></span><?php endif; ?></a></li>
    <?php if (empty($_SESSION['user_id'])) : ?>
      <li><a href="<?php echo $base_path; ?>/login" class="<?php echo ($active_page ?? '') === 'login' ? 'active' : ''; ?>">Login</a></li>
    <?php else : ?>
      <li><a href="<?php echo $base_path; ?>/orders" class="<?php echo ($active_page ?? '') === 'orders' ? 'active' : ''; ?>">My Orders</a></li>
      <li><a href="<?php echo $base_path; ?>/logout">Logout</a></li>
    <?php endif; ?>
  </ul>
</nav>
