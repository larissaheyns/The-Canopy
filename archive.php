<!DOCTYPE html>
<html lang="en">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<head>
    <title>The Canopy | Archive</title>
    <link href="theme.css" rel="stylesheet">
</head>
<body class="theme-dark">

    <?php include 'navbar.php'; ?>

    <div class="main-content">
        <div class="back-btn">
            <a href="dashboard.php"><i class="fa-solid fa-arrow-left" style="color: rgb(177, 151, 252);"></i></a>
        </div>
        <div class="page-wrap">

            <div class="archive-header">
                <p class="page-eyebrow" style="margin-bottom: 0;">Archive (Private)</p>
                <button type="button" class="info-btn" data-bs-toggle="modal" data-bs-target="#coreMemoryRuleModal" title="What is a Core Memory?">
                    <i class="fa-solid fa-info"></i>
                </button>
            </div>

            <div class="filter-row">
                <span class="filter-pill active">All</span>
                <span class="filter-pill">Core Memories</span>
                <span class="filter-pill">Hidden</span>
            </div>

            <div class="dream-grid">

                <div class="dream-card-dark">
                    <div class="d-flex align-items-start justify-content-between">
                        <p class="card-title">The visitor</p>
                        <span class="card-timestamp">2d ago</span>
                    </div>
                    <p class="card-body-text">An old friend visited me, but they had no face. I knew them, but not who they were.</p>
                    <div>
                        <span class="status-badge status-hidden"><i class="fa-regular fa-eye-slash"></i> Hidden from public</span>
                        <span class="status-badge status-core"><i class="fa-solid fa-star"></i> Core Memory</span>
                    </div>
                </div>

                <div class="dream-card-dark">
                    <div class="d-flex align-items-start justify-content-between">
                        <p class="card-title">The endless library</p>
                        <span class="card-timestamp">5d ago</span>
                    </div>
                    <p class="card-body-text">A maze of books that changed every step. No two paths led to the same room twice.</p>
                    <div>
                        <span class="status-badge status-core"><i class="fa-solid fa-star"></i> Core Memory</span>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Core Memory Rule modal -->
    <div class="modal fade" id="coreMemoryRuleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4" style="background: #1b1530; border: 1px solid rgba(167,139,250,0.2); border-radius: 22px; color: #e7e0ff;">
                <div class="modal-body text-center">
                    <p style="color:#e7e0ff; font-weight:700; font-size:1.05rem; letter-spacing:0.1em; text-transform:uppercase; margin-bottom: 1.75rem;">Core Memory Rule</p>

                    <div style="width:140px; height:140px; margin: 0 auto 1.75rem;">
                        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" style="filter: drop-shadow(0 0 14px rgba(196,181,253,0.6));">
                            <defs>
                                <radialGradient id="ruleGlow" cx="50%" cy="50%" r="50%">
                                    <stop offset="0%" stop-color="#c9b8fb" stop-opacity="0.55" />
                                    <stop offset="100%" stop-color="#c9b8fb" stop-opacity="0" />
                                </radialGradient>
                            </defs>
                            <circle cx="100" cy="100" r="95" fill="url(#ruleGlow)" />
                            <g fill="#e9e2ff" opacity="0.8">
                                <circle cx="45" cy="60" r="1.8" />
                                <circle cx="150" cy="45" r="1.6" />
                                <circle cx="165" cy="120" r="1.8" />
                                <circle cx="130" cy="165" r="1.6" />
                                <circle cx="55" cy="150" r="1.8" />
                                <circle cx="40" cy="105" r="1.4" />
                                <circle cx="120" cy="35" r="1.4" />
                            </g>
                            <path d="M100,35 L110,90 L165,100 L110,110 L100,165 L90,110 L35,100 L90,90 Z" fill="#ffffff" />
                            <path d="M100,60 L104,96 L140,100 L104,104 L100,140 L96,104 L60,100 L96,96 Z" fill="#c9b8fb" opacity="0.7" />
                        </svg>
                    </div>

                    <p style="color:#f1ecff; font-weight:600; font-size:1rem; margin-bottom: 0.75rem;">Core Memories are immutable.</p>
                    <p style="color:#b7a9d9; font-size:0.95rem; line-height:1.6;">They can be hidden from the world, but never deleted.</p>
                </div>
            </div>
        </div>
    </div>

<script src="https://kit.fontawesome.com/b2e0418e70.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>