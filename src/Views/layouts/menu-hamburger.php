<aside class="menu menu-hamburger hide">
    <div class="menu__submenu menu__submenu-v menu__submenu-close">
        <button class="menu__item menu__item-clickable prominent button-close"><span class="prominent__icon icon-close"></span></button>
    </div>

    <?php if(isset($menu)): ?>
        <?php foreach($menu as $submenu): ?>
            <?= "<nav class=\"menu__submenu menu__submenu-v\">" ?>
            <?php foreach($submenu as $item): ?>
                <?= "<a class=\"menu__item menu__item-clickable prominent\" href=\"{$item['href']}\"><span class=\"prominent__text\">{$item['text']}</span><span class=\"prominent__icon icon-{$item['icon']}\"></span></a>" ?>
            <?php endforeach; ?>
            <?= "</nav>" ?>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="menu__submenu menu__submenu-v">
        <button class="menu__item menu__item-clickable prominent button-switch-theme"><span class="prominent__text">Thème</span><span class="prominent__icon icon-theme"></span></button>
        <button class="menu__item menu__item-clickable prominent button-menu-tiny"><span class="prominent__text">Petit menu</span><span class="prominent__icon icon-menu"></span></button>
    </div>
</aside>
