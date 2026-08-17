<!DOCTYPE html>
<html lang="en">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<head>
    <title>The Canopy</title>
    <link href="theme.css" rel="stylesheet">
</head>
<body class="theme-light">

    <?php include 'navbar.php'; ?>

    <div class="main-content">
       

        <div class="page-header d-flex align-items-start justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <img class="avatar-circle" src="img/avatar-somnambulist.jpg" alt="Somnambulist">
                <div>
                    <p class="profile-name">Somnambulist</p>
                    <p class="profile-status mb-0">Lucid State</p>
                </div>
            </div>
            
        </div>

        <h2 class="tagline">Clarity is your superpower.</h2>

        <div class="d-flex card-row">
            <div class="dream-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <img class="avatar-circle small" src="img/avatar-drifter-42.jpg" alt="Drifter-42">
                        <p class="card-username">Drifter-42</p>
                    </div>
                    <span class="card-timestamp">2h ago</span>
                </div>
                <p class="card-body-text">I flew above the mountains and felt complete freedom.</p>
                <span class="dream-tag">#Lucid</span>
                <span class="dream-tag">#Flying</span>
            </div>

            <div class="dream-card technique-card">
                <p class="technique-label">Lucid Technique</p>
                <p class="technique-title">Reality Check</p>
                <p class="technique-text">Try counting your fingers twice. If the number changes, you're dreaming.</p>
            </div>

            <div class="dream-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <img class="avatar-circle small" src="img/avatar-drifter-91.jpg" alt="Drifter-91">
                        <p class="card-username">Drifter-91</p>
                    </div>
                    <span class="card-timestamp">4h ago</span>
                </div>
                <p class="card-body-text">I shaped the world with my thoughts today.</p>
                <span class="dream-tag">#Control</span>
                <span class="dream-tag">#Creation</span>
            </div>
        </div>
    </div>

<script src="https://kit.fontawesome.com/b2e0418e70.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>