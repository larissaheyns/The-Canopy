<?php
require_once 'config.php';

const RESONANCE_THRESHOLD = 5;

$actorMindId = $_SESSION['mind_id'] ?? $_SESSION['guest_mind_id'] ?? null;
$resonatedWithCount = 0;
$resonatedByCount = 0;

if ($actorMindId) {
    $withStmt = $conn->prepare(
        "SELECT COUNT(DISTINCT e.mind_id) AS cnt
         FROM resonances r
         JOIN emanations e ON r.emanations_id = e.emanations_id
         WHERE r.mind_id = ? AND e.mind_id != ?"
    );
    $withStmt->bind_param('ii', $actorMindId, $actorMindId);
    $withStmt->execute();
    $resonatedWithCount = (int)($withStmt->get_result()->fetch_assoc()['cnt'] ?? 0);
    $withStmt->close();

    $byStmt = $conn->prepare(
        "SELECT COUNT(DISTINCT r.mind_id) AS cnt
         FROM resonances r
         JOIN emanations e ON r.emanations_id = e.emanations_id
         WHERE e.mind_id = ? AND r.mind_id != ?"
    );
    $byStmt->bind_param('ii', $actorMindId, $actorMindId);
    $byStmt->execute();
    $resonatedByCount = (int)($byStmt->get_result()->fetch_assoc()['cnt'] ?? 0);
    $byStmt->close();
}

$rawBestCount = max($resonatedWithCount, $resonatedByCount);
$bestCount = min(RESONANCE_THRESHOLD, $rawBestCount);
$remaining = RESONANCE_THRESHOLD - $bestCount;
?>
<!DOCTYPE html>
<html lang="en">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<head>
    <title>The Canopy | Profile</title>
    <link href="theme.css" rel="stylesheet">
</head>
<body class="theme-dark">

    <?php include 'navbar.php'; ?>

    <div class="main-content stage">

        <p class="page-eyebrow" style="margin-bottom: 0;">Profile</p>

        <div class="center-stage">
            <div class="stage-card" style="padding: 55px 45px;">
                <p class="stage-heading" style="margin-bottom: 2.5rem;">Trust Growing</p>

                <div class="rings-wrap">
                    <svg viewBox="0 0 300 300">
                        <?php
                        $outerPositions = [
                            [150, 25], [268.9, 111.4], [223.5, 251.1], [76.5, 251.1], [31.1, 111.4],
                        ];
                        $innerPositions = [
                            [198.2, 83.7], [227.98, 175.3], [150, 232], [72.0, 175.3], [101.8, 83.7],
                        ];
                        ?>
                        <circle cx="150" cy="150" r="125" fill="none" stroke="rgba(167,139,250,0.3)" stroke-width="1.5" />
                        <?php foreach ($outerPositions as $i => $pos): ?>
                            <circle cx="<?php echo $pos[0]; ?>" cy="<?php echo $pos[1]; ?>" r="4"
                                    fill="<?php echo $i < $bestCount ? '#a78bfa' : 'rgba(167,139,250,0.25)'; ?>" />
                        <?php endforeach; ?>

                        <circle cx="150" cy="150" r="82" fill="none" stroke="rgba(167,139,250,0.22)" stroke-width="1.5" />
                        <?php foreach ($innerPositions as $i => $pos): ?>
                            <circle cx="<?php echo $pos[0]; ?>" cy="<?php echo $pos[1]; ?>" r="<?php echo $i < 3 ? 5 : 3; ?>"
                                    fill="<?php echo ($i + 5) < $bestCount ? '#c9b8fb' : 'rgba(201,184,251,0.3)'; ?>" />
                        <?php endforeach; ?>
                    </svg>
                    <div class="rings-center">
                        <div class="rings-count"><?php echo $bestCount; ?>/<?php echo RESONANCE_THRESHOLD; ?></div>
                        <div class="rings-label">Resonances</div>
                    </div>
                </div>

                <p class="stage-body">
                    <?php if (isset($_SESSION['mind_id'])): ?>
                        You are known to <?php echo $rawBestCount; ?> other mind<?php echo $rawBestCount === 1 ? '' : 's'; ?>.<br>
                        Keep connecting to deepen your trust.
                    <?php elseif ($remaining > 0): ?>
                        <?php echo $remaining; ?> more resonance<?php echo $remaining === 1 ? '' : 's'; ?><br>
                        and your mind will awaken.<br>
                        Keep connecting.
                    <?php else: ?>
                        Your mind is known.<br>
                        You're ready to awaken<br>
                        whenever you choose.
                    <?php endif; ?>
                </p>
            </div>
        </div>
    </div>

<script src="https://kit.fontawesome.com/b2e0418e70.js" crossorigin="anonymous"></script>
</body>
</html>