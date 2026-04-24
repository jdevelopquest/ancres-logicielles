<aside class="menu menu-hamburger hide">
    <div class="menu__submenu menu__submenu-v menu__submenu-close">
        <button class="menu__item menu__item-clickable prominent button-close" title="Fermer le menu"><span class="prominent__icon icon-close"></span></button>
    </div>

    <?php if(isset($menu)): ?>
        <?php foreach($menu as $submenu): ?>
            <nav class="menu__submenu menu__submenu-v">
            <?php foreach($submenu as $item): ?>
                <a class="menu__item menu__item-clickable prominent" href="<?= $item["href"] ?>" title="<?= $item["title"] ?>">
                    <span class="prominent__text"><?= $item["text"] ?></span>
                    <span class="prominent__icon icon-<?= $item["icon"] ?>"></span>
                </a>
            <?php endforeach; ?>
            </nav>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="menu__submenu menu__submenu-v">
        <button class="menu__item menu__item-clickable prominent button-switch-theme" title="Changer le thème"><span class="prominent__text">Thème</span><span class="prominent__icon icon-switch-theme"></span></button>
        <button class="menu__item menu__item-clickable prominent button-menu-tiny" title="Activer/Désactiver le menu flottant"><span class="prominent__text">Menu flottant</span><span class="prominent__icon icon-switch-on-off"></span></button>
    </div>
</aside>
