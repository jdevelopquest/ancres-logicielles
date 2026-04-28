<?php if (isset($idPost)): ?>
    <?php if (isset($modTools)): ?>
        <?php foreach ($modTools as $tool): ?>
            <button class="menu__item menu__item-clickable prominent button-post-<?= htmlspecialchars(
                $tool["action"],
            ) ?>"
                    title="<?= htmlspecialchars($tool["title"]) ?>"
                    data-id-post="<?= htmlspecialchars($idPost) ?>">
                <span class="prominent__icon icon-<?= htmlspecialchars(
                    $tool["icon"],
                ) ?>"></span>
            </button>
        <?php endforeach; ?>
    <?php endif; ?>
<?php endif; ?>
