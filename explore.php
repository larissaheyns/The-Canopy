<?php
include 'config.php';

$allowedTypes = ['Nightmare', 'Lucid', 'Liminal'];
$activeType = $_GET['type'] ?? 'All';
if (!in_array($activeType, $allowedTypes, true)) {
    $activeType = 'All';
}

$searchTerm = trim($_GET['q'] ?? '');

$sql = "SELECT e.emanations_id, e.title, e.content, e.dream_type, e.created_at,
               m.alias,
               (SELECT COUNT(*) FROM resonances r WHERE r.emanations_id = e.emanations_id) AS resonance_count
        FROM emanations e
        JOIN minds m ON e.mind_id = m.mind_id
        WHERE e.is_visible = 1";

$params = [];
$types = '';

if ($activeType !== 'All') {
    $sql .= " AND e.dream_type = ?";
    $params[] = $activeType;
    $types .= 's';
}

if ($searchTerm !== '') {
    $sql .= " AND (e.content LIKE ? OR e.title LIKE ? OR m.alias LIKE ?)";
    $like = '%' . $searchTerm . '%';
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $types .= 'sss';
}

$sql .= " ORDER BY e.created_at DESC";

$stmt = $conn->prepare($sql);
if ($types !== '') {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$dreams = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
$stmt->close();

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

            <form method="get" action="explore.php">
                <input type="hidden" name="type" value="<?php echo htmlspecialchars($activeType); ?>">
                <div class="search-bar">
                    <i class="fa-solid fa-magnifying-glass leading"></i>
                    <input type="text" name="q" placeholder="Search dreams or tags..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                    <button type="submit" class="search-btn" style="border:none;cursor:pointer;">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </form>

            <?php
            $filterOptions = ['All' => 'All', 'Nightmare' => 'Nightmare', 'Lucid' => 'Lucid', 'Liminal' => 'Liminal'];
            ?>
            <div class="filter-row">
                <?php foreach ($filterOptions as $value => $label): ?>
                    <a href="explore.php?type=<?php echo urlencode($value); ?><?php echo $searchTerm !== '' ? '&q=' . urlencode($searchTerm) : ''; ?>"
                       class="filter-pill <?php echo $activeType === $value ? 'active' : ''; ?>"
                       style="text-decoration:none;">
                        <?php echo htmlspecialchars($label); ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <div class="dream-grid">
                <?php if (empty($dreams)): ?>
                    <p class="card-timestamp">
                        <?php if ($activeType !== 'All' || $searchTerm !== ''): ?>
                            No dreams match your filters. <a href="explore.php" style="color:#cbb8ff;">Clear filters</a>.
                        <?php else: ?>
                            No dreams have been shared yet. Be the first to write one.
                        <?php endif; ?>
                    </p>
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
                <span class="resonate-count"><i class="fa-regular fa-heart"></i> <?php echo (int) $dream['resonance_count']; ?></span>
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
</body>
</html>