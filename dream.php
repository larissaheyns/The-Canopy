<!DOCTYPE html>
<html lang="en">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<head>
    <title>The Canopy | Interpret</title>
    <link href="theme.css" rel="stylesheet">
</head>
<body class="theme-dark">
    <?php

    $dream = [
          'username'  => 'Drifter-493',
          'avatar'    => 'img/avatar-drifter-493.jpg',
          'timestamp' => '2h ago',
          'body'      => "I was falling, endlessly. The wind screamed but I couldn't wake up.",
          'tags'      => ['#Falling', '#Anxiety'],
          'resonated' => false,
          'resonance_count' => 12,
      ];
 
      $comments = [
          [
              'username'  => 'Drifter-21',
              'avatar'    => 'img/avatar-drifter-21.jpg',
              'timestamp' => '1h ago',
              'body'      => "I've had this one too. It's always the falling that gets me.",
          ],
          [
              'username'  => 'Somnambulist',
              'avatar'    => 'img/avatar-somnambulist.jpg',
              'timestamp' => '35m ago',
              'body'      => 'The wind screaming detail is so vivid. Thank you for sharing this.',
          ],
      ];
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
                    <span class="resonate-btn <?php echo $dream['resonated'] ? 'active' : ''; ?>">
                        <i class="fa-<?php echo $dream['resonated'] ? 'solid' : 'regular'; ?> fa-heart"></i>
                        Resonate
                    </span>
                    <span class="card-timestamp"><?php echo (int) $dream['resonance_count']; ?> resonances</span>
                </div>
            </div>
 
            <div class="write-card" style="margin-top: 1.5rem;">
                <div class="write-card-header">
                    <p class="write-card-title">Comments</p>
                </div>
 
                <div class="comments-list">
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
 
                <form style="margin-top: 1rem;">
                    <div class="field-group">
                        <label class="field-label">ADD A COMMENT</label>
                        <textarea class="dream-textarea" name="comment_body" placeholder="Share your thoughts on this dream..." style="min-height: 90px;"></textarea>
                    </div>
 
                    <button type="submit" class="btn-publish">Post Comment</button>
                </form>
            </div>
 
        </div>
    </div>
 
<script src="https://kit.fontawesome.com/b2e0418e70.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>