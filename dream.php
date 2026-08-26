<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<head>
    <title>The Canopy | Interpret</title>
    <link href="theme.css" rel="stylesheet">
</head>
<body class="theme-dark">
    <?php

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

    $emanationsId = (int)($_GET['id'] ?? 0);
    $actorMindId = $_SESSION['mind_id'] ?? $_SESSION['guest_mind_id'] ?? null;

    $stmt = $conn->prepare(
        "SELECT e.emanations_id, e.content, e.dream_type, e.created_at, m.alias
         FROM emanations e
         JOIN minds m ON e.mind_id = m.mind_id
         WHERE e.emanations_id = ? AND e.is_visible = 1"
    );
    $stmt->bind_param('i', $emanationsId);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$row) {
        header('Location: explore.php');
        exit;
    }

    $countStmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM resonances WHERE emanations_id = ?");
    $countStmt->bind_param('i', $emanationsId);
    $countStmt->execute();
    $resonanceCount = (int)($countStmt->get_result()->fetch_assoc()['cnt'] ?? 0);
    $countStmt->close();

    $hasResonated = false;
    if ($actorMindId !== null) {
        $hasStmt = $conn->prepare(
            "SELECT resonances_id FROM resonances WHERE mind_id = ? AND emanations_id = ?"
        );
        $hasStmt->bind_param('ii', $actorMindId, $emanationsId);
        $hasStmt->execute();
        $hasResonated = (bool)$hasStmt->get_result()->fetch_assoc();
        $hasStmt->close();
    }

    $dream = [
          'username'  => $row['alias'] ?? 'Anonymous Drifter',
          'avatar'    => 'img/avatar-default.jpg',
          'timestamp' => time_ago($row['created_at']),
          'body'      => $row['content'],
          'tags'      => ['#' . $row['dream_type']],
          'resonated' => $hasResonated,
          'resonance_count' => $resonanceCount,
      ];

      $commentsStmt = $conn->prepare(
          "SELECT i.interpretation, i.created_at, m.alias
           FROM interpretations i
           JOIN minds m ON i.mind_id = m.mind_id
           WHERE i.emanations_id = ?
           ORDER BY i.created_at ASC"
      );
      $commentsStmt->bind_param('i', $emanationsId);
      $commentsStmt->execute();
      $commentsResult = $commentsStmt->get_result();

      $comments = [];
      while ($commentRow = $commentsResult->fetch_assoc()) {
          $comments[] = [
              'username'  => $commentRow['alias'] ?? 'Anonymous Drifter',
              'avatar'    => 'img/avatar-default.jpg',
              'timestamp' => time_ago($commentRow['created_at']),
              'body'      => $commentRow['interpretation'],
          ];
      }
      $commentsStmt->close();
    ?>
 
    <?php include 'navbar.php'; ?>
 
    <div class="main-content">
        <div class="page-wrap">
 
            <a href="explore.php" class="page-eyebrow" style="text-decoration:none; display:inline-flex; align-items:center; gap:8px;">
                <i class="fa-solid fa-arrow-left"></i> Back to Explore
            </a>
 
            <div class="dream-card-dark" style="margin-top: 1.5rem;">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <img class="avatar-orb" src="<?php echo htmlspecialchars($dream['avatar']); ?>" alt="<?php echo htmlspecialchars($dream['username']); ?>">
                        <p class="card-username"><?php echo htmlspecialchars($dream['username']); ?></p>
                    </div>
                    <div class="text-end">
                        <div class="card-timestamp mb-1"><?php echo htmlspecialchars($dream['timestamp']); ?></div>
                    </div>
                </div>
 
                <p class="card-body-text" style="font-size: 1.05rem; margin-top: 1rem;">
                    <?php echo htmlspecialchars($dream['body']); ?>
                </p>
 
                <div class="mb-3">
                    <?php foreach ($dream['tags'] as $tag): ?>
                        <span class="dream-tag"><?php echo htmlspecialchars($tag); ?></span>
                    <?php endforeach; ?>
                </div>
 
                <div class="d-flex align-items-center gap-3">
                    <?php if ($actorMindId !== null): ?>
                        <form method="post" action="resonate.php" style="margin:0;">
                            <input type="hidden" name="emanations_id" value="<?php echo $emanationsId; ?>">
                            <input type="hidden" name="redirect" value="dream.php?id=<?php echo $emanationsId; ?>">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                            <button type="submit" class="resonate-btn <?php echo $dream['resonated'] ? 'active' : ''; ?>">
                                <i class="fa-<?php echo $dream['resonated'] ? 'solid' : 'regular'; ?> fa-heart"></i>
                                Resonate
                            </button>
                        </form>
                    <?php else: ?>
                        <span class="resonate-btn">
                            <i class="fa-regular fa-heart"></i>
                            Resonate
                        </span>
                    <?php endif; ?>
                    <span class="card-timestamp"><?php echo (int) $dream['resonance_count']; ?> resonances</span>
                </div>
            </div>
 
            <div class="write-card" style="margin-top: 1.5rem;">
                <div class="write-card-header">
                    <p class="write-card-title">Comments</p>
                </div>
 
                <div class="comments-list">
                    <?php if (empty($comments)): ?>
                        <p class="comment-text" style="color: #786a99;">No comments yet. Be the first to share your thoughts.</p>
                    <?php endif; ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="comment-item">
                            <img class="avatar-orb avatar-orb-sm" src="<?php echo htmlspecialchars($comment['avatar']); ?>" alt="<?php echo htmlspecialchars($comment['username']); ?>">
                            <div class="comment-body">
                                <div class="d-flex align-items-center gap-2">
                                    <p class="card-username mb-0"><?php echo htmlspecialchars($comment['username']); ?></p>
                                    <span class="card-timestamp"><?php echo htmlspecialchars($comment['timestamp']); ?></span>
                                </div>
                                <p class="comment-text"><?php echo htmlspecialchars($comment['body']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
 
                <?php if ($actorMindId !== null): ?>
                <form method="post" action="comment.php" style="margin-top: 1rem;">
                    <input type="hidden" name="emanations_id" value="<?php echo $emanationsId; ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                    <div class="field-group">
                        <label class="field-label">ADD A COMMENT</label>
                        <textarea class="dream-textarea" name="comment_body" placeholder="Share your thoughts on this dream..." style="min-height: 90px;" required></textarea>
                    </div>
 
                    <button type="submit" class="btn-publish">Post Comment</button>
                </form>
                <?php else: ?>
                <p class="comment-text" style="margin-top: 1rem;">
                    <a href="index.php">Visit the home page</a> to receive your identity before commenting.
                </p>
                <?php endif; ?>
            </div>
 
        </div>
    </div>
 
<script src="https://kit.fontawesome.com/b2e0418e70.js" crossorigin="anonymous"></script>
</body>
</html>