<?php
require_once 'config.php';

if (!isset($_SESSION['mind_id'])) {
    header('Location: login.php');
    exit;
}

$mindId = $_SESSION['mind_id'];

$allowedFilters = ['all', 'core', 'hidden'];
$activeFilter = $_GET['filter'] ?? 'all';
if (!in_array($activeFilter, $allowedFilters, true)) {
    $activeFilter = 'all';
}

$sql = "SELECT e.emanations_id, e.title, e.content, e.dream_type, e.is_core_memory, e.is_visible, e.created_at,
               (SELECT COUNT(*) FROM resonances r WHERE r.emanations_id = e.emanations_id) AS resonance_count
        FROM emanations e
        WHERE e.mind_id = ?";

if ($activeFilter === 'core') {
    $sql .= " AND e.is_core_memory = 1";
} elseif ($activeFilter === 'hidden') {
    $sql .= " AND e.is_visible = 0";
} else {
    $sql .= " AND (e.is_visible = 0 OR e.is_core_memory = 1)";
}

$sql .= " ORDER BY e.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $mindId);
$stmt->execute();
$dreams = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

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
    <title>The Canopy | Archive</title>
    <link href="theme.css" rel="stylesheet">
</head>
<body class="theme-dark">

    <?php include 'navbar.php'; ?>

    <div class="main-content">
        <div class="back-btn">
            <a href="dashboard.php"><i class="fa-solid fa-arrow-left" style="color: rgb(177, 151, 252);"></i></a>
        </div>
        <div class="page-wrap">

            <div class="archive-header">
                <p class="page-eyebrow" style="margin-bottom: 0;">Archive (Private)</p>
                <button type="button" class="info-btn" data-bs-toggle="modal" data-bs-target="#coreMemoryRuleModal" title="What is a Core Memory?">
                    <i class="fa-solid fa-info"></i>
                </button>
            </div>

            <div class="filter-row">
                <?php
                $filterLabels = ['all' => 'All', 'core' => 'Core Memories', 'hidden' => 'Hidden'];
                foreach ($filterLabels as $value => $label):
                ?>
                    <a href="archive.php?filter=<?php echo urlencode($value); ?>"
                       class="filter-pill <?php echo $activeFilter === $value ? 'active' : ''; ?>"
                       style="text-decoration:none;">
                        <?php echo htmlspecialchars($label); ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <div class="dream-grid">
                <?php if (empty($dreams)): ?>
                    <p class="card-timestamp">Nothing here yet.</p>
                <?php endif; ?>

                <?php foreach ($dreams as $dream): ?>
                    <div class="dream-card-dark">
                        <div class="d-flex align-items-start justify-content-between">
                            <p class="card-title"><?php echo htmlspecialchars($dream['title'] ?: 'Untitled Dream'); ?></p>
                            <span class="card-timestamp"><?php echo time_ago($dream['created_at']); ?></span>
                        </div>
                        <p class="card-body-text"><?php echo htmlspecialchars($dream['content']); ?></p>
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <?php if (!$dream['is_visible']): ?>
                                    <span class="status-badge status-hidden"><i class="fa-regular fa-eye-slash"></i> Hidden from public</span>
                                <?php endif; ?>
                                <?php if ($dream['is_core_memory']): ?>
                                    <span class="status-badge status-core"><i class="fa-solid fa-star"></i> Core Memory</span>
                                <?php endif; ?>
                            </div>
                            <?php if (!$dream['is_visible']): ?>
                                <form method="post" action="restore_dream.php" style="margin:0;">
                                    <input type="hidden" name="emanations_id" value="<?php echo (int) $dream['emanations_id']; ?>">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                    <button type="submit" class="archive-link" style="cursor:pointer; font-family:inherit;">
                                        <i class="fa-solid fa-arrow-rotate-left"></i> Restore to Public
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="modal fade" id="coreMemoryRuleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4" style="background: #1b1530; border: 1px solid rgba(167,139,250,0.2); border-radius: 22px; color: #e7e0ff;">
                <div class="modal-body text-center">
                    <p style="color:#e7e0ff; font-weight:700; font-size:1.05rem; letter-spacing:0.1em; text-transform:uppercase; margin-bottom: 1.75rem;">Core Memory Rule</p>

                    <div style="width:140px; height:140px; margin: 0 auto 1.75rem;">
                        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" style="filter: drop-shadow(0 0 14px rgba(196,181,253,0.6));">
                            <defs>
                                <radialGradient id="ruleGlow" cx="50%" cy="50%" r="50%">
                                    <stop offset="0%" stop-color="#c9b8fb" stop-opacity="0.55" />
                                    <stop offset="100%" stop-color="#c9b8fb" stop-opacity="0" />
                                </radialGradient>
                            </defs>
                            <circle cx="100" cy="100" r="95" fill="url(#ruleGlow)" />
                            <g fill="#e9e2ff" opacity="0.8">
                                <circle cx="45" cy="60" r="1.8" />
                                <circle cx="150" cy="45" r="1.6" />
                                <circle cx="165" cy="120" r="1.8" />
                                <circle cx="130" cy="165" r="1.6" />
                                <circle cx="55" cy="150" r="1.8" />
                                <circle cx="40" cy="105" r="1.4" />
                                <circle cx="120" cy="35" r="1.4" />
                            </g>
                            <path d="M100,35 L110,90 L165,100 L110,110 L100,165 L90,110 L35,100 L90,90 Z" fill="#ffffff" />
                            <path d="M100,60 L104,96 L140,100 L104,104 L100,140 L96,104 L60,100 L96,96 Z" fill="#c9b8fb" opacity="0.7" />
                        </svg>
                    </div>

                    <p style="color:#f1ecff; font-weight:600; font-size:1rem; margin-bottom: 0.75rem;">Core Memories are immutable.</p>
                    <p style="color:#b7a9d9; font-size:0.95rem; line-height:1.6;">They can be hidden from the world, but never deleted.</p>
                </div>
            </div>
        </div>
    </div>

<script src="https://kit.fontawesome.com/b2e0418e70.js" crossorigin="anonymous"></script>
</body>
</html>