<?php
require_once 'config.php';

if (isset($_SESSION['mind_id'])) {
    header('Location: dashboard.php');
    exit;
}

$errors = [];
$aliasValue = '';

const RESONANCE_THRESHOLD = 5;

$guestMindId = $_SESSION['guest_mind_id'] ?? null;
$resonatedWithCount = 0;
$resonatedByCount = 0;

if ($guestMindId) {
    $withStmt = $conn->prepare(
        "SELECT COUNT(DISTINCT e.mind_id) AS cnt
         FROM resonances r
         JOIN emanations e ON r.emanations_id = e.emanations_id
         WHERE r.mind_id = ? AND e.mind_id != ?"
    );
    $withStmt->bind_param('ii', $guestMindId, $guestMindId);
    $withStmt->execute();
    $resonatedWithCount = (int)($withStmt->get_result()->fetch_assoc()['cnt'] ?? 0);
    $withStmt->close();

    $byStmt = $conn->prepare(
        "SELECT COUNT(DISTINCT r.mind_id) AS cnt
         FROM resonances r
         JOIN emanations e ON r.emanations_id = e.emanations_id
         WHERE e.mind_id = ? AND r.mind_id != ?"
    );
    $byStmt->bind_param('ii', $guestMindId, $guestMindId);
    $byStmt->execute();
    $resonatedByCount = (int)($byStmt->get_result()->fetch_assoc()['cnt'] ?? 0);
    $byStmt->close();
}

$bestCount = max($resonatedWithCount, $resonatedByCount);
$canAwaken = $bestCount >= RESONANCE_THRESHOLD;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$canAwaken) {
    $errors[] = "You're not ready to awaken yet.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $canAwaken) {
    $aliasValue = trim($_POST['alias'] ?? '');
    $password   = (string)($_POST['password'] ?? '');
    $token      = $_POST['csrf_token'] ?? '';

    if (!hash_equals($_SESSION['csrf_token'], $token)) {
        $errors[] = 'Your session expired. Please try again.';
    }

    if ($aliasValue === '') {
        $errors[] = 'Choose an alias.';
    } elseif (!preg_match('/^[A-Za-z0-9_\-]{3,30}$/', $aliasValue)) {
        $errors[] = 'Alias must be 3-30 characters (letters, numbers, - and _ only).';
    }

    if ($password === '') {
        $errors[] = 'Choose a password.';
    } elseif (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }

    if (empty($errors)) {
        $guestMindId = $_SESSION['guest_mind_id'] ?? null;

        $check = $conn->prepare(
            "SELECT mind_id FROM minds WHERE alias = ? AND password_hash IS NOT NULL AND mind_id != ?"
        );
        $excludeId = $guestMindId ?? 0;
        $check->bind_param('si', $aliasValue, $excludeId);
        $check->execute();
        $taken = $check->get_result()->fetch_assoc();
        $check->close();

        if ($taken) {
            $errors[] = 'That alias is already taken. Try another.';
        } else {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $newMindId = null;

            if ($guestMindId) {
                $update = $conn->prepare(
                    "UPDATE minds
                     SET alias = ?, password_hash = ?, user_role = 'Awakened', last_active = NOW()
                     WHERE mind_id = ? AND password_hash IS NULL"
                );
                $update->bind_param('ssi', $aliasValue, $passwordHash, $guestMindId);
                $update->execute();

                if ($update->affected_rows > 0) {
                    $newMindId = $guestMindId;
                }
                $update->close();
            }

            if ($newMindId === null) {
                $cosmicSignature = 'mind-' . bin2hex(random_bytes(16));
                $insert = $conn->prepare(
                    "INSERT INTO minds (cosmic_signature, alias, password_hash, user_role, last_active)
                     VALUES (?, ?, ?, 'Awakened', NOW())"
                );
                $insert->bind_param('sss', $cosmicSignature, $aliasValue, $passwordHash);

                if ($insert->execute()) {
                    $newMindId = $insert->insert_id;
                } else {
                    $errors[] = 'That alias was just taken. Try another.';
                }
                $insert->close();
            }

            if ($newMindId !== null) {
                session_regenerate_id(true);
                $_SESSION['mind_id'] = $newMindId;
                unset($_SESSION['guest_alias'], $_SESSION['guest_mind_id'], $_SESSION['csrf_token']);
                header('Location: dashboard.php');
                exit;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<head>
    <title>The Canopy | Choose Your Alias</title>
    <link href="theme.css?v=<?php echo filemtime(__DIR__ . '/theme.css'); ?>" rel="stylesheet">
</head>
<body class="theme-dark">

    <?php include 'navbar.php'; ?>

    <div class="main-content stage">
        <div class="center-stage">
            <div class="stage-card" style="max-width: 440px;">
                <p class="stage-heading" style="font-size: 1.35rem;">Choose Your Alias</p>

                <div class="alias-avatar">
                    <img src="img/Landing.png" alt="Your alias">
                </div>

                <?php if (!empty($errors)): ?>
                    <div class="form-error">
                        <?php foreach ($errors as $error): ?>
                            <p><?php echo htmlspecialchars($error); ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if (!$canAwaken): ?>
                    <p class="stage-body">
                        A mind must be known before it can awaken.<br>
                        Resonate with 5 different dreamers, or have 5 different
                        dreamers resonate with you.
                    </p>

                    <div class="field-group" style="text-align:center;">
                        <p class="signup-footer" style="text-align:center;">
                            Resonated with: <strong><?php echo $resonatedWithCount; ?>/<?php echo RESONANCE_THRESHOLD; ?></strong><br>
                            Resonated by: <strong><?php echo $resonatedByCount; ?>/<?php echo RESONANCE_THRESHOLD; ?></strong>
                        </p>
                    </div>

                    <a href="explore.php" class="btn-signup">Go Resonate</a>

                    <p class="signup-footer">
                        Already awakened? <a href="login.php">Log back in</a>.
                    </p>
                <?php else: ?>
                    <form method="post" action="signup.php" autocomplete="off">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

                        <div class="field-group">
                            <label class="field-label">Create an alias</label>
                            <input type="text" name="alias" class="alias-input" placeholder="Somnambulist" value="<?php echo htmlspecialchars($aliasValue); ?>" maxlength="30" required>
                        </div>

                        <div class="field-group">
                            <label class="field-label">Create a password</label>
                            <input type="password" name="password" class="password-input" placeholder="At least 8 characters" minlength="8" required>
                        </div>

                        <button type="submit" class="btn-signup">Awaken</button>
                    </form>

                    <p class="signup-footer">
                        This is your true signature.<br>
                        Welcome home.<br>
                        Already awakened? <a href="login.php">Log back in</a>.
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>

<script src="https://kit.fontawesome.com/b2e0418e70.js" crossorigin="anonymous"></script>
</body>
</html>