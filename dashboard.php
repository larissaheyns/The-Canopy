<?php
require_once 'config.php';

if (!isset($_SESSION['mind_id'])) {
    header('Location: login.php');
    exit;
}

$mindId = $_SESSION['mind_id'];

$stateLabels = [
    'Liminal'   => 'Liminal State',
    'Lucid'     => 'Lucid State',
    'Nightmare' => 'Nightmare State',
];

$mindStmt = $conn->prepare("SELECT alias, dominant_state FROM minds WHERE mind_id = ?");
$mindStmt->bind_param('i', $mindId);
$mindStmt->execute();
$mindRow = $mindStmt->get_result()->fetch_assoc();
$mindStmt->close();

$alias = $mindRow['alias'] ?? 'Somnambulist';
$stateLabel = $stateLabels[$mindRow['dominant_state'] ?? 'Liminal'] ?? 'Liminal State';

$resStmt = $conn->prepare(
    "SELECT COUNT(*) AS cnt FROM resonances r
     JOIN emanations e ON r.emanations_id = e.emanations_id
     WHERE e.mind_id = ?"
);
$resStmt->bind_param('i', $mindId);
$resStmt->execute();
$resonancesReceived = (int)($resStmt->get_result()->fetch_assoc()['cnt'] ?? 0);
$resStmt->close();

$statsStmt = $conn->prepare(
    "SELECT COUNT(*) AS total, SUM(is_core_memory) AS core_count
     FROM emanations WHERE mind_id = ?"
);
$statsStmt->bind_param('i', $mindId);
$statsStmt->execute();
$statsRow = $statsStmt->get_result()->fetch_assoc();
$statsStmt->close();

$dreamsShared = (int)($statsRow['total'] ?? 0);
$coreMemories = (int)($statsRow['core_count'] ?? 0);

$dreamsStmt = $conn->prepare(
    "SELECT e.emanations_id, e.title, e.content, e.dream_type, e.is_core_memory, e.created_at,
            (SELECT COUNT(*) FROM resonances r WHERE r.emanations_id = e.emanations_id) AS resonance_count
     FROM emanations e
     WHERE e.mind_id = ? AND e.is_visible = 1
     ORDER BY e.created_at DESC"
);
$dreamsStmt->bind_param('i', $mindId);
$dreamsStmt->execute();
$dreams = $dreamsStmt->get_result()->fetch_all(MYSQLI_ASSOC);
$dreamsStmt->close();

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
    <title>The Canopy | Your Sanctuary</title>
    <link href="theme.css" rel="stylesheet">
</head>
<body class="theme-dark">

    <?php include 'navbar.php'; ?>

    <div class="main-content">
        <div class="page-wrap">

            <div class="profile-header">
                <div class="d-flex align-items-center gap-3">
                    <img class="avatar-photo" src="img/avatar-default.jpg" style="height:80px; width:80px; border-radius:50%;" alt="<?php echo htmlspecialchars($alias); ?>">
                    <div>
                        <p class="page-eyebrow" style="margin-bottom: 2px;"><?php echo htmlspecialchars(strtoupper($alias)); ?></p>
                        <p class="page-subtext"><?php echo htmlspecialchars($stateLabel); ?> &middot; Your Sanctuary</p>
                    </div>
                </div>
                <a href="archive.php" class="archive-link"> <i class="fa-solid fa-box-archive"></i>  View Archive</a>
            </div>

            <div class="stat-row">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fa-regular fa-heart"></i></div>
                    <div>
                        <div class="stat-number"><?php echo $resonancesReceived; ?></div>
                        <div class="stat-label">Resonances Received</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fa-solid fa-cloud-moon"></i></div>
                    <div>
                        <div class="stat-number"><?php echo $dreamsShared; ?></div>
                        <div class="stat-label">Dreams Shared</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fa-regular fa-star"></i></div>
                    <div>
                        <div class="stat-number"><?php echo $coreMemories; ?></div>
                        <div class="stat-label">Core Memories</div>
                    </div>
                </div>
            </div>

            <p class="section-heading">Your Dreams</p>

            <div class="dream-grid">
                <?php if (empty($dreams)): ?>
                    <p class="card-timestamp">You haven't shared any dreams yet. <a href="interpret.php" style="color:#cbb8ff;">Write your first one</a>.</p>
                <?php endif; ?>

                <?php foreach ($dreams as $dream): ?>
                    <?php
                    $modalId = $dream['is_core_memory'] ? 'coreMemoryModal' . $dream['emanations_id'] : 'archiveConfirmModal' . $dream['emanations_id'];
                    ?>
                    <div class="dream-card-dark">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <p class="dream-title"><?php echo htmlspecialchars($dream['title'] ?: 'Untitled Dream'); ?></p>
                                <span class="card-timestamp"><?php echo time_ago($dream['created_at']); ?></span>
                            </div>
                            <div class="card-actions">
                                <span class="resonate-count"><i class="fa-regular fa-heart"></i> <?php echo (int) $dream['resonance_count']; ?></span>
                                <button type="button" class="icon-btn" data-bs-toggle="modal" data-bs-target="#<?php echo $modalId; ?>" title="Hide">
                                    <i class="fa-solid fa-eye-slash" style="color: rgb(163, 161, 167);"></i>
                                </button>
                            </div>
                        </div>
                        <p class="card-body-text"><?php echo htmlspecialchars($dream['content']); ?></p>
                        <div class="card-footer-row">
                            <div>
                                <span class="dream-tag">#<?php echo htmlspecialchars($dream['dream_type']); ?></span>
                            </div>
                            <?php if ($dream['is_core_memory']): ?>
                                <span class="core-badge"><i class="fa-solid fa-star"></i> Core Memory</span>
                            <?php else: ?>
                                <span></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="modal fade" id="<?php echo $modalId; ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content p-3">
                                <div class="modal-body">
                                    <?php if ($dream['is_core_memory']): ?>
                                        <div class="modal-icon-warn"><i class="fa-solid fa-triangle-exclamation"></i></div>
                                        <p class="modal-title-warn mb-2">This is a Core Memory.<br>It cannot be un-dreamed.</p>
                                        <p class="modal-body-text mb-4">You may hide it from the public, but it must remain a part of you.</p>
                                    <?php else: ?>
                                        <div class="modal-icon-warn" style="background: rgba(167,139,250,0.12); border-color: rgba(167,139,250,0.4); color: #cbb8ff;">
                                            <i class="fa-solid fa-box-archive"></i>
                                        </div>
                                        <p class="modal-title-warn mb-2" style="color: #cbb8ff;">Move this dream to your Archive?</p>
                                        <p class="modal-body-text mb-4">It will be removed from the public feed and only visible to you.</p>
                                    <?php endif; ?>
                                    <form method="post" action="hide_dream.php">
                                        <input type="hidden" name="emanations_id" value="<?php echo (int) $dream['emanations_id']; ?>">
                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                        <button type="submit" class="<?php echo $dream['is_core_memory'] ? 'btn-hide-public' : 'btn-archive-confirm'; ?> mb-2">
                                            <?php echo $dream['is_core_memory'] ? 'Hide from Public' : 'Move to Archive'; ?>
                                        </button>
                                    </form>
                                    <button type="button" class="btn-cancel-link" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

<script src="https://kit.fontawesome.com/b2e0418e70.js" crossorigin="anonymous"></script>
</body>
</html>