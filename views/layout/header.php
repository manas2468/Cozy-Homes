<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $pageTitle ?? 'Cozyhomes Collections'; ?></title>
  <link rel="stylesheet" href="/css/style.css">
  <script src="/assets/js/main.js" defer></script>
</head>
<body>
<header>
  <nav class="main-nav">
    <h1>Cozyhomes</h1>
    <div class="nav-links">
      <!-- FIXED: Changed href="/" to the correct path based on your site's URL -->
      <a href="/product/catalog">Catalog</a> 
      <?php if (isset($_SESSION['userId'])): ?>
        <a href="/cart/view">
          Cart 
          <span id="cartIconBadge" class="badge">
            <?php 
            // In a real application, fetch the current cart count here
            echo $_SESSION['cartCount'] ?? 0; 
            ?>
          </span>
        </a>
        <a href="/auth/logout">Logout</a>
      <?php else: ?>
        <a href="/auth/login">Login</a>
        <a href="/auth/register">Register</a>
      <?php endif; ?>
    </div>
  </nav>
</header>
<main>