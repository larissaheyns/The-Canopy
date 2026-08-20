<?php
include 'config.php';

$result = $conn->query(
    "SELECT e.emanations_id, e.title, e.content, e.dream_type, e.created_at,
            m.alias
     FROM emanations e
     JOIN minds m ON e.mind_id = m.mind_id
     WHERE e.is_visible = 1
     ORDER BY e.created_at DESC"
);

$dreams = [];
if ($result) {
    $dreams = $result->fetch_all(MYSQLI_ASSOC);
}

function time_ago($datetime) {
    $diff = time() - strtotime($datetime);
 
    if ($diff < 60) {
        return 'just now';
    } elseif ($diff < 3600) {
        return floor($diff / 60) . 'm ago';
    } elseif ($diff < 86400) {
        return floor($diff / 3600) . 'h ago';
    } else {
        return floor($diff / 86400) . 'd ago';
    }
}
?>
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
                <span class="filter-pill">Liminal</span>
            </div>

            <div class="dream-grid">
                <?php if (empty($dreams)): ?>
                    <p class="card-timestamp">No dreams have been shared yet. Be the first to write one.</p>
                <?php endif; ?>

                <?php foreach ($dreams as $dream): ?>
    <a href="dream.php?id=<?php echo (int) $dream['emanations_id']; ?>"
       class="dream-card-dark" style="text-decoration:none; color:inherit; display:block;">
        <div class="d-flex align-items-start justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <img class="avatar-orb" src="img/avatar-default.jpg" alt="<?php echo htmlspecialchars($dream['alias']); ?>">
                <p class="card-username"><?php echo htmlspecialchars($dream['alias']); ?></p>
            </div>
            <div class="text-end">
                <div class="card-timestamp mb-1"><?php echo time_ago($dream['created_at']); ?></div>
                <span class="resonate-btn"><i class="fa-regular fa-heart"></i> Resonate</span>
            </div>
        </div>
        <p class="card-body-text"><?php echo htmlspecialchars($dream['content']); ?></p>
        <span class="dream-tag">#<?php echo htmlspecialchars($dream['dream_type']); ?></span>
    </a>
<?php endforeach; ?>
                        
            </div>
        </div>
    </div>

<script src="https://kit.fontawesome.com/b2e0418e70.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>