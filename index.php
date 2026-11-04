<?php 
    include 'config.php';

    $isLoggedIn = isset($_SESSION['mind_id']);

   $moodPages = [
    'Liminal'   => 'liminal.php',
    'Lucid'     => 'lucid.php',
    'Nightmare' => 'nightmare.php',
];

if ($isLoggedIn) {
    $stmt = $conn->prepare("SELECT alias, dominant_state FROM minds WHERE mind_id = ?");
    $stmt->bind_param('i', $_SESSION['mind_id']);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    $displayAlias  = $row['alias'] ?? 'Drifter';
    $dominantState = $row['dominant_state'] ?? 'Liminal';
    } else {
    $displayAlias  = $_SESSION['guest_alias'] ?? 'Anonymous Drifter';
    $dominantState = 'Liminal';

    if (isset($_SESSION['guest_mind_id'])) {
        $stmt = $conn->prepare("SELECT dominant_state FROM minds WHERE mind_id = ?");
        $stmt->bind_param('i', $_SESSION['guest_mind_id']);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $dominantState = $row['dominant_state'] ?? 'Liminal';
    }
    }

$homeUrl = $moodPages[$dominantState] ?? 'liminal.php';
?>
<!DOCTYPE html>
<html lang="en">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="theme.css?v=<?php echo filemtime(__DIR__ . '/theme.css'); ?>" rel="stylesheet">
<head>
    <title>The Canopy</title>
</head>

<body class="index-background"> 
    
    <p class="index-eyebrow" style="margin-bottom: 0;">The Canopy</p>
    <div class="index-information">
        <div class="index-text">
            <p>You are a</p>
            <p class="index-name"><?php echo htmlspecialchars($displayAlias); ?></p>
            <p>You drift unseen.<br>
            Write. Read. Resonance.<br>
            Awaken when you're ready.</p>
        </div>
        <a href="<?php echo htmlspecialchars($homeUrl); ?>" class="index-btn">Enter The Canopy</a>

        <?php if (!$isLoggedIn): ?>
            <p class="signup-footer">You are anonymous</p>
        <?php endif; ?>
    </div>

<script src="https://kit.fontawesome.com/b2e0418e70.js" crossorigin="anonymous"></script>
</body>
</html>