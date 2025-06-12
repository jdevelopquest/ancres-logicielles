<?php if (isset($postStatus)): ?>
    <?php foreach ($postStatus as $status): ?>
        <span class="prominent__icon icon-<?= $status["icon"] ?>"
              title="<?= $status["title"] ?>"></span>
    <?php endforeach; ?>
<?php endif; ?>