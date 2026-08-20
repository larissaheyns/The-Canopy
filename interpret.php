<?php 
    include 'config.php';

    $isLoggedIn = isset($_SESSION['mind_id']);

    $guestAliases = [
    'Drifter-77', 'Wanderer-42', 'Dreamer-18', 'Voyager-63',
    'Sleeper-09', 'Astral-31', 'Nebula-84', 'Eclipse-27',
    'Orbit-56', 'Comet-13', 'Stardust-91', 'Moonwalker-38',
    ];
 
    $errors = [];

    $titleValue      = '';
    $bodyValue       = '';
    $dreamTypeValue  = '';
    $coreMemoryValue = false;
 
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
    $titleValue     = trim($_POST['title'] ?? '');
    $bodyValue      = trim($_POST['dream_body'] ?? '');
    $dreamTypeValue = trim($_POST['dream_type'] ?? '');

    $coreMemoryValue = isset($_POST['coreMemory']);

    if ($bodyValue === '') {
        $errors[] = 'Your dream can\'t be empty.';
    }
 
    $allowedDreamTypes = ['Nightmare', 'Lucid', 'Liminal'];
    if (!in_array($dreamTypeValue, $allowedDreamTypes, true)) {
        $errors[] = 'Please choose a valid dream type.';
    }

    $isCoreMemory = $isLoggedIn ? $coreMemoryValue : false;

    if ($isLoggedIn) {
        $mindId = $_SESSION['mind_id'];
    } elseif (isset($_SESSION['guest_mind_id'])) {
        $mindId = $_SESSION['guest_mind_id'];
    } else {
        $randomAlias = $guestAliases[array_rand($guestAliases)];
        $lookup = $conn->prepare("SELECT mind_id FROM minds WHERE alias = ?");
        $lookup->bind_param('s', $randomAlias);
        $lookup->execute();
        $lookupResult = $lookup->get_result()->fetch_assoc();
        $lookup->close();
 
        if ($lookupResult) {
            $mindId = $lookupResult['mind_id'];
            $_SESSION['guest_alias']   = $randomAlias;
            $_SESSION['guest_mind_id'] = $mindId;
        } else {
            $errors[] = 'Guest posting isn\'t set up yet. Please run seed-guest-aliases.sql, or sign in.';
        }
    }

    if (empty($errors)) {
        $stmt = $conn->prepare(
            "INSERT INTO emanations (mind_id, title, content, dream_type, is_core_memory, is_visible)
             VALUES (?, ?, ?, ?, ?, 1)"
    );

    $titleToStore = $titleValue === '' ? null : $titleValue;
    $isCoreMemoryInt = $isCoreMemory ? 1 : 0;

    $stmt->bind_param(
            'isssi',
            $mindId,
            $titleToStore,
            $bodyValue,
            $dreamTypeValue,
            $isCoreMemoryInt
        );

    if ($stmt->execute()) {
            $stmt->close();
            $updateState = $conn->prepare("UPDATE minds SET dominant_state = ? WHERE mind_id = ?");
            $updateState->bind_param('si', $dreamTypeValue, $mindId);
            $updateState->execute();
            $updateState->close();      
            header('Location: explore.php');
            exit;
        } else {
            $errors[] = 'Something went wrong saving your dream. Please try again.';
        }
 
        $stmt->close();
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<head>
    <title>The Canopy | Interpret</title>
    <link href="theme.css" rel="stylesheet">
</head>
<body class="theme-dark">

    <?php include 'navbar.php'; ?>

    <div class="main-content">
        <div class="page-wrap">

            <p class="page-eyebrow" style="margin-bottom: 1.75rem;">Interpret</p>

            <div class="write-card">
                <div class="write-card-header">
                    <p class="write-card-title">Write a Dream</p>
                   
                </div>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger" role="alert" style="margin-bottom: 1rem;">
                        <ul style="margin-bottom: 0; padding-left: 1.1rem;">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="interpret.php">
                    <div class="field-group">
                        <label class="field-label">TITLE (OPTIONAL)</label>
                        <input type="text" name="title" class="dream-input" placeholder="e.g. The Visitor"
                               value="<?php echo htmlspecialchars($titleValue); ?>">
                    </div>
 
                    <div class="field-group">
                        <label class="field-label">YOUR DREAM</label>
                        <textarea name="dream_body" class="dream-textarea"
                                  placeholder="An old friend visited me, but they had no face. I knew them, but not who they were."><?php echo htmlspecialchars($bodyValue); ?></textarea>
                    </div>

                <form>
                    

                    <div class="field-group">
                        <label class="field-label">DREAM TYPE</label>
                        <select class="dream-input" name="dream_type" id="dreamType">
                            <option value="" disabled <?php echo $dreamTypeValue === '' ? 'selected' : ''; ?>>Choose one...</option>
                            <option value="Nightmare" <?php echo $dreamTypeValue === 'Nightmare' ? 'selected' : ''; ?>>Nightmare</option>
                            <option value="Lucid" <?php echo $dreamTypeValue === 'Lucid' ? 'selected' : ''; ?>>Lucid</option>
                            <option value="Liminal" <?php echo $dreamTypeValue === 'Liminal' ? 'selected' : ''; ?>>Balanced</option>
                        </select>
                    </div>

                   <div class="field-group">
                        <label class="field-label">TAGS / RESONANCES</label>
                        <input type="text" class="tag-input" placeholder="Add a tag and press enter...">
                        <div class="tag-pills">
                            <span class="dream-tag">#Mystery <i class="fa-solid fa-xmark remove-tag"></i></span>
                            <span class="dream-tag">#Nostalgia <i class="fa-solid fa-xmark remove-tag"></i></span>
                        </div>
                    </div>

                     <?php if ($isLoggedIn): ?>
                        <div class="core-memory-row">
                            <input type="checkbox" id="coreMemory" name="coreMemory" <?php echo $coreMemoryValue ? 'checked' : ''; ?>>
                            <label for="coreMemory" style="cursor: pointer;">
                                <div class="core-memory-label">Deserves to be remembered</div>
                                <div class="core-memory-sub">Mark as Core Memory</div>
                            </label>
                        </div>
                    <?php endif; ?>

                    <button type="submit" class="btn-publish">Publish Dream</button>
                </form>
            </div>
        </div>
    </div>

<script src="https://kit.fontawesome.com/b2e0418e70.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>