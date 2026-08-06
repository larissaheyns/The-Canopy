<!DOCTYPE html>
<html lang="en">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<head>
    <title>The Canopy | Your Sanctuary</title>
    <link href="theme.css" rel="stylesheet">
</head>
<body class="theme-dark">

    <?php include 'navbar.php'; ?>

    <div class="main-content">
        <div class="page-wrap">

            <div class="profile-header">
                <div class="d-flex align-items-center gap-3">
                    <img class="avatar-photo" src="img/avatar-somnambulist.jpg" style="height:80px; width:80px; border-radius:50%;" alt="Somnambulist">
                    <div>
                        <p class="page-eyebrow" style="margin-bottom: 2px;">Somnambulist</p>
                        <p class="page-subtext">Lucid State · Your Sanctuary</p>
                    </div>
                </div>
                <a href="archive.php" class="archive-link"> <i class="fa-solid fa-box-archive"></i>  View Archive</a>
            </div>

            <div class="stat-row">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fa-regular fa-heart"></i></div>
                    <div>
                        <div class="stat-number">128</div>
                        <div class="stat-label">Resonances Received</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fa-solid fa-cloud-moon"></i></div>
                    <div>
                        <div class="stat-number">12</div>
                        <div class="stat-label">Dreams Shared</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fa-regular fa-star"></i></div>
                    <div>
                        <div class="stat-number">2</div>
                        <div class="stat-label">Core Memories</div>
                    </div>
                </div>
            </div>

            <p class="section-heading">Your Dreams</p>

            <div class="dream-grid">

                <!-- Core Memory dream -->
                <div class="dream-card-dark">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <p class="dream-title">The visitor</p>
                            <span class="card-timestamp">2d ago</span>
                        </div>
                        <div class="card-actions">
                            <span class="resonate-count"><i class="fa-regular fa-heart"></i> 12</span>
                            <button type="button" class="icon-btn" data-bs-toggle="modal" data-bs-target="#coreMemoryModal" title="Delete">
                                <i class="fa-solid fa-eye-slash" style="color: rgb(163, 161, 167);"></i>
                            </button>
                        </div>
                    </div>
                    <p class="card-body-text">An old friend visited me, but they had no face. I knew them, but not who they were.</p>
                    <div class="card-footer-row">
                        <div>
                            <span class="dream-tag">#Mystery</span>
                            <span class="dream-tag">#Nostalgia</span>
                        </div>
                        <span class="core-badge"><i class="fa-solid fa-star"></i> Core Memory</span>
                    </div>
                </div>

                <!-- Regular dream -->
                <div class="dream-card-dark">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <p class="dream-title">The endless library</p>
                            <span class="card-timestamp">5d ago</span>
                        </div>
                        <div class="card-actions">
                            <span class="resonate-count"><i class="fa-regular fa-heart"></i> 6</span>
                            <button type="button" class="icon-btn" data-bs-toggle="modal" data-bs-target="#archiveConfirmModal" title="Delete">
                               <i class="fa-solid fa-eye-slash" style="color: rgb(163, 161, 167);"></i>
                            </button>
                        </div>
                    </div>
                    <p class="card-body-text">A maze of books that changed every step. No two paths led to the same room twice.</p>
                    <div class="card-footer-row">
                        <div>
                            <span class="dream-tag">#Maze</span>
                            <span class="dream-tag">#Books</span>
                        </div>
                        <span></span>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Core Memory protection modal -->
    <div class="modal fade" id="coreMemoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-3">
                <div class="modal-body">
                    <div class="modal-icon-warn"><i class="fa-solid fa-triangle-exclamation"></i></div>
                    <p class="modal-title-warn mb-2">This is a Core Memory.<br>It cannot be un-dreamed.</p>
                    <p class="modal-body-text mb-4">You may hide it from the public, but it must remain a part of you.</p>
                    <button type="button" class="btn-hide-public mb-2" data-bs-dismiss="modal">Hide from Public</button>
                    <button type="button" class="btn-cancel-link" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Regular archive confirm modal -->
    <div class="modal fade" id="archiveConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-3">
                <div class="modal-body">
                    <div class="modal-icon-warn" style="background: rgba(167,139,250,0.12); border-color: rgba(167,139,250,0.4); color: #cbb8ff;">
                        <i class="fa-solid fa-box-archive"></i>
                    </div>
                    <p class="modal-title-warn mb-2" style="color: #cbb8ff;">Move this dream to your Archive?</p>
                    <p class="modal-body-text mb-4">It will be removed from the public feed and only visible to you.</p>
                    <button type="button" class="btn-archive-confirm mb-2" data-bs-dismiss="modal">Move to Archive</button>
                    <button type="button" class="btn-cancel-link" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

<script src="https://kit.fontawesome.com/b2e0418e70.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>