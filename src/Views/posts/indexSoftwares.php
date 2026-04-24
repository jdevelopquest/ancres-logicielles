<section class="content">
    <div class="content__subcontent prominent">
        <h2 class="prominent__text">Logiciels</h2>
        <?php if (isset($moderatorTools)): ?>
        <span class="prominent__icon icon-<?= $moderatorTools["addArticle"]["icon"] ?>"
                                      title="<?= $moderatorTools["addArticle"]["title"] ?>"></span>
        <?php endif; ?>
    </div>
    <article class="content__subcontent">
        <div class="prominent">
            <h3 class="prominent__text">À consulter</h3>
        </div>

        <?php if (isset($softwares)): ?>
            <div class="menu">
                <?php foreach ($softwares as $software): ?>
                    <div class="menu__submenu menu__submenu-v">
                        <a class="menu__item menu__item-clickable prominent" href="<?= $software["href"] ?>">
                            <span class="prominent__text"><?= $software["softwareName"] ?></span>
                            <?php foreach ($software["status"] as $status): ?>
                                <span class="prominent__icon icon-<?= $status["icon"] ?>"
                                      title="<?= $status["title"] ?>"></span>
                            <?php endforeach; ?>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="regular">
                <p class="regular__text">Aucunes fiches logicielles.</p>
            </div>
        <?php endif; ?>
    </article>
</section>