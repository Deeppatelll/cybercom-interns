<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $page_title ?? 'EasyCart'; ?></title>
  <link rel="stylesheet" href="<?php echo $base_path; ?>/assets/css/main.css">
</head>
<body>
  <?php include __DIR__ . '/../partials/header.php'; ?>

  <?php require $viewPath; ?>

  <?php include __DIR__ . '/../partials/footer.php'; ?>

  <script>window.EASYCART_BASE_PATH = "<?php echo $base_path; ?>";</script>
  <script src="<?php echo $base_path; ?>/assets/js/auth.js"></script>
  <script src="<?php echo $base_path; ?>/assets/js/shipping.js"></script>
  <script src="<?php echo $base_path; ?>/assets/js/cart.js"></script>
  <script src="<?php echo $base_path; ?>/assets/js/checkout.js"></script>
  <script src="<?php echo $base_path; ?>/assets/js/gallery.js"></script>
  <script src="<?php echo $base_path; ?>/assets/js/filters.js"></script>
  <script src="<?php echo $base_path; ?>/assets/js/add-to-cart.js"></script>
</body>
</html>
