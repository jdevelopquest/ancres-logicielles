<section class="content">
    <div class="content__subcontent prominent">
        <h2 class="prominent__text">Accueil</h2>
    </div>
    <article class="content__subcontent">
        <div class="prominent">
            <h3 class="prominent__text">À consulter</h3>
        </div>

        <?php if (isset($menu)): ?>
            <?php foreach ($menu as $submenu): ?>
                <?= "<nav class=\"menu__submenu menu__submenu-v\">" ?>
                <?php foreach ($submenu as $item): ?>
                    <?= "<a class=\"menu__item menu__item-clickable prominent\" href=\"{$item['href']}\"><span class=\"prominent__text\">{$item['text']}</span><span class=\"prominent__icon icon-{$item['icon']}\"></span></a>" ?>
                <?php endforeach; ?>
                <?= "</nav>" ?>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="regular">
                <p class="regular__text">
                    Aucunes fiches logicielles.
                </p>
            </div>
        <?php endif; ?>
    </article>
</section>