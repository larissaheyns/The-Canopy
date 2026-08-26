<?php
require_once 'config.php';

$actorMindId = $_SESSION['mind_id'] ?? $_SESSION['guest_mind_id'] ?? null;
$emanationsId = (int)($_POST['emanations_id'] ?? 0);
$redirect = 'dream.php?id=' . $emanationsId;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $actorMindId !== null && $emanationsId > 0) {
    $token = $_POST['csrf_token'] ?? '';
    $body = trim($_POST['comment_body'] ?? '');

    if (isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token) && $body !== '') {
        $check = $conn->prepare("SELECT emanations_id FROM emanations WHERE emanations_id = ? AND is_visible = 1");
        $check->bind_param('i', $emanationsId);
        $check->execute();
        $exists = $check->get_result()->fetch_assoc();
        $check->close();

        if ($exists) {
            $insert = $conn->prepare(
                "INSERT INTO interpretations (mind_id, emanations_id, interpretation) VALUES (?, ?, ?)"
            );
            $insert->bind_param('iis', $actorMindId, $emanationsId, $body);
            $insert->execute();
            $insert->close();
        }
    }
}

header('Location: ' . $redirect);
exit;