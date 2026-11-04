<?php
/**
 *
 * @param array  $dream     row with emanations_id, content, created_at, alias
 * @param string $cardClass 'dream-card' (light pages) or 'dream-card-dark'
 * @param string $tag       mood label shown as the tag, e.g. 'Nightmare'
 */
function render_landing_card(array $dream, string $cardClass, string $tag): void
{
    $id    = (int) $dream['emanations_id'];
    $alias = htmlspecialchars($dream['alias']);
    ?>
    <div class="<?php echo htmlspecialchars($cardClass); ?> landing-card">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <img class="avatar-circle small" src="img/avatar-default.jpg" alt="<?php echo $alias; ?>">
                <p class="card-username"><?php echo $alias; ?></p>
            </div>
            <span class="card-timestamp"><?php echo time_ago($dream['created_at']); ?></span>
        </div>

        <p class="card-body-text dream-text"><?php echo nl2br(htmlspecialchars($dream['content'])); ?></p>

        <div class="landing-card-footer">
            <span class="dream-tag">#<?php echo htmlspecialchars($tag); ?></span>
            <button type="button" class="show-more-btn" aria-expanded="false" hidden>Show more</button>
        </div>

        <a class="stretched-link" href="dream.php?id=<?php echo $id; ?>" aria-label="Read the full dream by <?php echo $alias; ?>"></a>
    </div>
    <?php
}
