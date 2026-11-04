<?php
require_once 'config.php';

if (isset($_SESSION['mind_id'])) {
    header('Location: dashboard.php');
    exit;
}

$errors = [];
$aliasValue = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aliasValue = trim($_POST['alias'] ?? '');
    $password   = (string)($_POST['password'] ?? '');
    $token      = $_POST['csrf_token'] ?? '';

    if (!hash_equals($_SESSION['csrf_token'], $token)) {
        $errors[] = 'Your session expired. Please try again.';
    } elseif ($aliasValue === '' || $password === '') {
        $errors[] = 'Enter your alias and password.';
    } else {
        $stmt = $conn->prepare(
            "SELECT mind_id, password_hash FROM minds WHERE alias = ? AND password_hash IS NOT NULL"
        );
        $stmt->bind_param('s', $aliasValue);
        $stmt->execute();
        $mind = $stmt->get_result()->fetch_assoc();
        $stmt->close();


        if (!$mind || !password_verify($password, $mind['password_hash'])) {
            $errors[] = 'Incorrect alias or password.';
        } else {
            $update = $conn->prepare("UPDATE minds SET last_active = NOW() WHERE mind_id = ?");
            $update->bind_param('i', $mind['mind_id']);
            $update->execute();
            $update->close();

            session_regenerate_id(true);
            $_SESSION['mind_id'] = $mind['mind_id'];
            unset($_SESSION['guest_alias'], $_SESSION['guest_mind_id'], $_SESSION['csrf_token']);
            header('Location: dashboard.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<head>
    <title>The Canopy | Awaken Again</title>
    <link href="theme.css?v=<?php echo filemtime(__DIR__ . '/theme.css'); ?>" rel="stylesheet">
</head>
<body class="theme-dark">

    <?php include 'navbar.php'; ?>

    <div class="main-content stage">
        <div class="center-stage">
            <div class="stage-card" style="max-width: 440px;">
                <p class="stage-heading" style="font-size: 1.35rem;">Welcome Back</p>

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

                <form method="post" action="login.php" autocomplete="off">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

                    <div class="field-group">
                        <label class="field-label">Alias</label>
                        <input type="text" name="alias" class="alias-input" placeholder="Somnambulist" value="<?php echo htmlspecialchars($aliasValue); ?>" maxlength="30" required>
                    </div>

                    <div class="field-group">
                        <label class="field-label">Password</label>
                        <input type="password" name="password" class="password-input" placeholder="••••••••" required>
                    </div>

                    <button type="submit" class="btn-signup">Awaken</button>
                </form>

                <p class="signup-footer">
                    Your mind remembers the way.<br>
                    New here? <a href="signup.php">Choose an alias</a>.
                </p>
            </div>
        </div>
    </div>

<script src="https://kit.fontawesome.com/b2e0418e70.js" crossorigin="anonymous"></script>
</body>
</html>