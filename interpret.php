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
                    <div class="write-card-icons">
                        <i class="fa-solid fa-sliders"></i>
                        <i class="fa-solid fa-bookmark"></i>
                    </div>
                </div>

                <form>
                    <div class="field-group">
                        <label class="field-label">TITLE (OPTIONAL)</label>
                        <input type="text" class="dream-input" placeholder="e.g. The Visitor">
                    </div>

                    <div class="field-group">
                        <label class="field-label">YOUR DREAM</label>
                        <textarea class="dream-textarea" placeholder="An old friend visited me, but they had no face. I knew them, but not who they were."></textarea>
                    </div>

                    <div class="field-group">
                        <label class="field-label">TAGS / RESONANCES</label>
                        <input type="text" class="tag-input" placeholder="Add a tag and press enter...">
                        <div class="tag-pills">
                            <span class="dream-tag">#Mystery <i class="fa-solid fa-xmark remove-tag"></i></span>
                            <span class="dream-tag">#Nostalgia <i class="fa-solid fa-xmark remove-tag"></i></span>
                        </div>
                    </div>

                    <!-- <div class="core-memory-row">
                        <input type="checkbox" id="coreMemory">
                        <label for="coreMemory" style="cursor: pointer;">
                            <div class="core-memory-label">Deserves to be remembered</div>
                            <div class="core-memory-sub">Mark as Core Memory</div>
                        </label>
                    </div> -->

                    <button type="submit" class="btn-publish">Publish Dream</button>
                </form>
            </div>
        </div>
    </div>

<script src="https://kit.fontawesome.com/b2e0418e70.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>