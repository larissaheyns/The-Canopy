<?php 
    include 'config.php';

    $isLoggedIn = isset($_SESSION['mind_id']);

    if ($isLoggedIn) {
    $stmt = $conn->prepare("SELECT alias FROM minds WHERE mind_id = ?");
    $stmt->bind_param('i', $_SESSION['mind_id']);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    $displayAlias = $row['alias'] ?? 'Drifter';
} else {
    $displayAlias = $_SESSION['guest_alias'] ?? 'Anonymous Drifter';
}

$liminalBackgrounds = ['img/liminal-background.png', 'img/liminal-background2.png'];
if (!isset($_SESSION['liminal_bg'])) {
    $_SESSION['liminal_bg'] = $liminalBackgrounds[array_rand($liminalBackgrounds)];
}
$bgImage = $_SESSION['liminal_bg'];

$result = $conn->query(
    "SELECT e.emanations_id, e.content, e.created_at, m.alias
     FROM emanations e
     JOIN minds m ON e.mind_id = m.mind_id
     WHERE e.is_visible = 1 AND e.dream_type = 'Liminal'
     ORDER BY e.created_at DESC
     LIMIT 12"
);
$dreams = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
 
function time_ago($datetime) {
    $diff = time() - strtotime($datetime);
    if ($diff < 60) return 'just now';
    if ($diff < 3600) return floor($diff / 60) . 'm ago';
    if ($diff < 86400) return floor($diff / 3600) . 'h ago';
    return floor($diff / 86400) . 'd ago';
}
?>
<!DOCTYPE html>
<html lang="en">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<head>
    <title>The Canopy</title>
    <link href="theme.css" rel="stylesheet">
</head>
<body class="theme-liminal" style="background-image: url('<?php echo htmlspecialchars($bgImage); ?>');">

    <?php include 'navbar.php'; ?>

    <div class="main-content">
        <div class="moon-glow"></div>

        <div class="page-header d-flex align-items-start justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <img class="avatar-circle" src="img/avatar-default.jpg" alt="<?php echo htmlspecialchars($displayAlias); ?>">
                <div>
                    <p class="profile-name-liminal"><?php echo htmlspecialchars($displayAlias); ?></p>
                    <p class="profile-status-liminal mb-0">Liminal State</p>
                </div>
            </div>
            
        </div>

        <h2 class="tagline-liminal">"Memories drift. Click to grasp"</h2>

        <div class="d-flex card-row">
            <?php if (empty($dreams)): ?>
                <p class="card-timestamp">No liminal dreams have been shared yet.</p>
            <?php endif; ?>
 
            <?php foreach ($dreams as $dream): ?>
                <a href="dream.php?id=<?php echo (int) $dream['emanations_id']; ?>"
                   class="dream-card" style="text-decoration:none; color:inherit; display:block;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <img class="avatar-circle small" src="img/avatar-default.jpg" alt="<?php echo htmlspecialchars($dream['alias']); ?>">
                            <p class="card-username"><?php echo htmlspecialchars($dream['alias']); ?></p>
                        </div>
                        <span class="card-timestamp"><?php echo time_ago($dream['created_at']); ?></span>
                    </div>
                    <p class="card-body-text"><?php echo nl2br(htmlspecialchars($dream['content'])); ?></p>
                    <span class="dream-tag">#Liminal</span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

<script src="https://kit.fontawesome.com/b2e0418e70.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>