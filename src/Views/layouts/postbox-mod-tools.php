<?php if (isset($idPost)): ?>
    <?php if (isset($modTools)): ?>
        <?php foreach ($modTools as $tool): ?>
            <button class="menu__item menu__item-clickable prominent button-post-<?= $tool["action"] ?>"
                    title="<?= $tool["title"] ?>"
                    data-id-post="<?= $idPost ?>">
                <span class="prominent__icon icon-<?= $tool["icon"] ?>"></span>
            </button>
        <?php endforeach; ?>
    <?php endif; ?>
<?php endif; ?>