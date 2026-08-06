<!DOCTYPE html>
<html lang="en">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<head>
    <title>The Canopy | Explore</title>
    <link href="theme.css" rel="stylesheet">
</head>
<body class="theme-dark">

    <?php include 'navbar.php'; ?>

    <div class="main-content">
        <div class="page-wrap">

            <p class="page-eyebrow">Global Dreams</p>

            <div class="search-bar">
                <i class="fa-solid fa-magnifying-glass leading"></i>
                <input type="text" placeholder="Search dreams or tags...">
                <div class="search-btn"><i class="fa-solid fa-magnifying-glass"></i></div>
            </div>

            <div class="filter-row">
                <span class="filter-pill active">All</span>
                <span class="filter-pill">Nightmare</span>
                <span class="filter-pill">Lucid</span>
                <span class="filter-pill">Dream</span>
                <span class="filter-pill">Anomaly</span>
            </div>

            <div class="dream-grid">
                <div class="dream-card-dark">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <img class="avatar-orb" src="img/avatar-drifter-493.jpg" alt="Drifter-493">
                            <p class="card-username">Drifter-493</p>
                        </div>
                        <div class="text-end">
                            <div class="card-timestamp mb-1">2h ago</div>
                            <span class="resonate-btn"><i class="fa-regular fa-heart"></i> Resonate</span>
                        </div>
                    </div>
                    <p class="card-body-text">I was falling, endlessly. The wind screamed but I couldn't wake up.</p>
                    <span class="dream-tag">#Falling</span>
                    <span class="dream-tag">#Anxiety</span>
                </div>

                <div class="dream-card-dark">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <img class="avatar-orb" src="img/avatar-drifter-21.jpg" alt="Drifter-21">
                            <p class="card-username">Drifter-21</p>
                        </div>
                        <div class="text-end">
                            <div class="card-timestamp mb-1">3h ago</div>
                            <span class="resonate-btn"><i class="fa-regular fa-heart"></i> Resonate</span>
                        </div>
                    </div>
                    <p class="card-body-text">I found an old house familiar, but it wasn't mine.</p>
                    <span class="dream-tag">#Nostalgia</span>
                    <span class="dream-tag">#Memory</span>
                </div>

                <div class="dream-card-dark">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <img class="avatar-orb" src="img/avatar-drifter-8.jpg" alt="Drifter-8">
                            <p class="card-username">Drifter-8</p>
                        </div>
                        <div class="text-end">
                            <div class="card-timestamp mb-1">5h ago</div>
                            <span class="resonate-btn"><i class="fa-regular fa-heart"></i> Resonate</span>
                        </div>
                    </div>
                    <p class="card-body-text">The sky split open and poured light instead of rain. No one else seemed to notice.</p>
                    <span class="dream-tag">#Anomaly</span>
                    <span class="dream-tag">#Sky</span>
                </div>
            </div>
        </div>
    </div>

<script src="https://kit.fontawesome.com/b2e0418e70.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>