<?php
require_once 'config.php';

$actorMindId = $_SESSION['mind_id'] ?? $_SESSION['guest_mind_id'] ?? null;

$redirect = $_POST['redirect'] ?? 'explore.php';

if (!preg_match('#^[a-zA-Z0-9_\-]+\.php(\?[a-zA-Z0-9_=&\-]*)?$#', $redirect)) {
    $redirect = 'explore.php';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $actorMindId !== null) {
    $emanationsId = (int)($_POST['emanations_id'] ?? 0);
    $token = $_POST['csrf_token'] ?? '';

    if ($emanationsId > 0 && isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token)) {
        $owner = $conn->prepare("SELECT mind_id FROM emanations WHERE emanations_id = ?");
        $owner->bind_param('i', $emanationsId);
        $owner->execute();
        $ownerRow = $owner->get_result()->fetch_assoc();
        $owner->close();

        if ($ownerRow && (int)$ownerRow['mind_id'] !== (int)$actorMindId) {
            $existing = $conn->prepare(
                "SELECT resonances_id FROM resonances WHERE mind_id = ? AND emanations_id = ?"
            );
            $existing->bind_param('ii', $actorMindId, $emanationsId);
            $existing->execute();
            $existingRow = $existing->get_result()->fetch_assoc();
            $existing->close();

            if ($existingRow) {
                $del = $conn->prepare("DELETE FROM resonances WHERE resonances_id = ?");
                $del->bind_param('i', $existingRow['resonances_id']);
                $del->execute();
                $del->close();
            } else {
                $tagType = 'heart';
                $ins = $conn->prepare(
                    "INSERT INTO resonances (emanations_id, mind_id, tag_type) VALUES (?, ?, ?)"
                );
                $ins->bind_param('iis', $emanationsId, $actorMindId, $tagType);
                $ins->execute();
                $ins->close();
            }
        }
    }
}

header('Location: ' . $redirect);
exit;