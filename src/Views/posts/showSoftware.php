<section class="content">
    <div class="content__subcontent prominent">
        <h2 class="prominent__text">Logiciel</h2>
    </div>
    <article class="content__subcontent">
        <?php if (isset($software)): ?>

            <div class="prominent">
                <h3 class="prominent__text">
                    <span class="prominent__text"><?= $software["softwareName"] ?></span>
                </h3>
                <div class="postbox postbox-status postbox-status-<?= $software["idPost"] ?>">
                    <?php foreach ($software["status"] as $status): ?>
                        <span class="prominent__icon icon-<?= $status["icon"] ?>"
                              title="<?= $status["title"] ?>"></span>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="regular">
                <?php foreach ($software["summary"] as $summaryPart) : ?>
                    <p class="regular__text"><?= $summaryPart ?></p>
                <?php endforeach; ?>
            </div>

            <?php if (isset($softwareModTools)): ?>
                <aside class="menu">
                    <div class="menu__submenu menu__submenu-h menu__submenu-r postbox postbox-mod-tools-<?= $software["idPost"] ?>">
                        <?php foreach ($softwareModTools as $tool): ?>
                            <button class="menu__item menu__item-clickable prominent button-post-<?= $tool["action"] ?>"
                                    title="<?= $tool["title"] ?>"
                                    data-id-post="<?= $software["idPost"] ?>">
                                <span class="prominent__icon icon-<?= $tool["icon"] ?>"></span>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </aside>
            <?php endif; ?>

        <?php else: ?>
            <div class="regular">
                <p class="regular__text">Fiche vide !</p>
            </div>
        <?php endif ?>
    </article>
</section>
