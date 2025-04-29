<?php if (isset($software)): ?>
    <?php if (isset($softwareModTools)): ?>
        <?php foreach ($softwareModTools as $tool): ?>
            <button class="menu__item menu__item-clickable prominent button-post-<?= $tool["action"] ?>"
                    title="<?= $tool["title"] ?>"
                    data-id-post="<?= $software["idPost"] ?>">
                <span class="prominent__icon icon-<?= $tool["icon"] ?>"></span>
            </button>
        <?php endforeach; ?>
    <?php endif; ?>
<?php endif; ?>