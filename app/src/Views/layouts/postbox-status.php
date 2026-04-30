<?php
$postStatus = $postStatus ?? null; ?>

<?php if (isset($postStatus)): ?>
    <?php foreach ($postStatus as $status): ?>
        <span class="prominent__icon icon-<?= htmlspecialchars(
            $status["icon"],
        ) ?>"
              title="<?= htmlspecialchars($status["title"]) ?>"></span>
    <?php endforeach; ?>
<?php endif; ?>
