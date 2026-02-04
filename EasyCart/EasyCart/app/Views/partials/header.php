<header>
  <div class="header-container">
    <div class="logo">🛒 EasyCart</div>
    <?php include __DIR__ . '/nav.php'; ?>
    <form method="GET" action="<?php echo $base_path; ?>/products" style="display: flex; align-items: center; margin-left: 2rem;">
      <input type="text" name="search" placeholder="Search products..." style="padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 4px; width: 200px;" value="<?php echo isset($search_keyword) ? htmlspecialchars($search_keyword) : ''; ?>">
      <button type="submit" style="margin-left: 8px; padding: 8px 16px; background-color: var(--primary-color); color: white; border: none; border-radius: 4px; cursor: pointer;">Search</button>
    </form>
  </div>
</header>
