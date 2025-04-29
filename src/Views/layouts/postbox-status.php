<?php if (isset($software)): ?>
    <?php foreach ($software["status"] as $status): ?>
        <span class="prominent__icon icon-<?= $status["icon"] ?>"
              title="<?= $status["title"] ?>"></span>
    <?php endforeach; ?>
<?php endif; ?>