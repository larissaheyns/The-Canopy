<!DOCTYPE html>
<html lang="en">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<head>
    <title>The Canopy | Choose Your Alias</title>
    <link href="theme.css" rel="stylesheet">
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

                <form>
                    <div class="field-group">
                        <label class="field-label">Create an alias</label>
                        <input type="text" class="alias-input" value="Somnambulist">
                    </div>

                    <div class="field-group">
                        <label class="field-label">Create a password</label>
                        <input type="password" class="password-input" value="dreamwalker">
                    </div>

                    <a href="dashboard.php" class="btn-signup">Awaken</a>
                </form>

                <p class="signup-footer">
                    This is your true signature.<br>
                    Welcome home.
                </p>
            </div>
        </div>
    </div>

<script src="https://kit.fontawesome.com/b2e0418e70.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>