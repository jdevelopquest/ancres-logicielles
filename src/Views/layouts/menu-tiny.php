<aside class="menu menu-tiny hide">
    <?php if(isset($menu)): ?>
        <?php foreach($menu as $submenu): ?>
            <?= "<nav class=\"menu__submenu menu__submenu-v\">" ?>
            <?php foreach($submenu as $item): ?>
                <?= "<a class=\"menu__item menu__item-clickable prominent\" href=\"{$item['href']}\" title=\"{$item['href']}\"><span class=\"prominent__icon icon-{$item['icon']}\"></span></a>" ?>
            <?php endforeach; ?>
            <?= "</nav>" ?>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="menu__submenu menu__submenu-v">
        <button class="menu__item menu__item-clickable prominent button-switch-theme" title="Changer de thème"><span class="prominent__icon icon-theme"></span></button>
        <button class="menu__item menu__item-clickable prominent button-menu-tiny" title="Fermer ce menu"><span class="prominent__icon icon-menu"></span></button>
    </div>
</aside>

