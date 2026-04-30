<?php
$menuModTools = $menuModTools ?? null;
$softwares = $softwares ?? null;
?>

<section class="content">
    <div class="content__subcontent prominent">
        <h2 class="prominent__text">Logiciels</h2>
    </div>

    <?php if (isset($menuModTools)): ?>
        <?php foreach ($menuModTools as $submenuName => $submenu): ?>
            <nav class="menu__submenu menu__submenu-h menu__submenu-r">
                <?php foreach ($submenu as $item): ?>
                    <a class="menu__item menu__item-clickable prominent" href="<?= htmlspecialchars(
                        $item["href"],
                    ) ?>"
                       title="<?= htmlspecialchars($item["title"]) ?>">
                        <span class="prominent__icon icon-<?= htmlspecialchars(
                            $item["icon"],
                        ) ?>"></span>
                    </a>
                <?php endforeach; ?>
            </nav>
        <?php endforeach; ?>
    <?php endif; ?>

    <article class="content__subcontent">
        <div class="prominent">
            <h3 class="prominent__text">À consulter</h3>
        </div>

        <?php if (isset($softwares)): ?>
            <div class="menu">
                <?php foreach ($softwares as $software): ?>
                    <div class="menu__submenu menu__submenu-v">

                        <a class="menu__item menu__item-clickable prominent" href="<?= htmlspecialchars(
                            $software["href"],
                        ) ?>">
                            <span class="prominent__text"><?= htmlspecialchars(
                                $software["softwareName"],
                            ) ?></span>
                            <?php foreach ($software["status"] as $status): ?>
                                <span class="prominent__icon icon-<?= htmlspecialchars(
                                    $status["icon"],
                                ) ?>"
                                      title="<?= htmlspecialchars(
                                          $status["title"],
                                      ) ?>"></span>
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
