<?php
require_once 'config.php';

$actorMindId = $_SESSION['mind_id'] ?? null;
$emanationsId = (int)($_POST['emanations_id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $actorMindId !== null && $emanationsId > 0) {
    $token = $_POST['csrf_token'] ?? '';

    if (isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token)) {
        // Only the dream's own author can restore it.
        $stmt = $conn->prepare(
            "UPDATE emanations SET is_visible = 1 WHERE emanations_id = ? AND mind_id = ?"
        );
        $stmt->bind_param('ii', $emanationsId, $actorMindId);
        $stmt->execute();
        $stmt->close();
    }
}

header('Location: archive.php');
exit;