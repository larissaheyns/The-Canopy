<!DOCTYPE html>
<html lang="en">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="theme.css" rel="stylesheet">
<head>
    <title>The Canopy</title>
</head>

<body>
  <?php
    $__current = basename($_SERVER['PHP_SELF']);
 
    
    $__homePages = ['liminal.php', 'nightmare.php', 'lucid.php'];
    $__isHome = in_array($__current, $__homePages, true);     
    ?>
      <svg width="0" height="0" style="position:absolute;">
  <mask id="navMask" maskUnits="userSpaceOnUse" x="0" y="0" width="150" height="<?php echo $__svgHeight; ?>">
    <rect x="0" y="0" width="150" height="<?php echo $__svgHeight; ?>" fill="white"/>
    
  </mask>
</svg>

    <div class="sidebar d-flex flex-column vh-100 flex-shrink-0 p-3">
  <ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
      <a href="liminal.php" class="nav-link <?php echo $__isHome ? 'active' : ''; ?>" aria-current="page"><i class="fa-regular fa-house"></i><h6 style="text-align: center;">Home</h6></a>
    </li>
    <li>
      <a href="explore.php" class="nav-link link-dark <?php echo $__current === 'explore.php' ? 'active' : ''; ?>"><i class="fa-regular fa-star"></i><h6 style="text-align: center;">Explore</h6></a>
    </li>
    <li>
      <a href="interpret.php" class="nav-link link-dark <?php echo $__current === 'interpret.php' ? 'active' : ''; ?>"><i class="fa-regular fa-cloud"></i><h6 style="text-align: center;">Interpret</h6></a>
    </li>
    <li>
      <a href="profile.php" class="nav-link link-dark <?php echo $__current === 'profile.php' ? 'active' : ''; ?>"><i class="fa-regular fa-user"></i><h6 style="text-align: center;">Profile</h6></a>
    </li>
  </ul>
</div>
 
<script src="https://kit.fontawesome.com/b2e0418e70.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>