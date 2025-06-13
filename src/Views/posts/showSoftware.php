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
                    <?= $this->renderPartial("layouts/postbox-status", ["postStatus" => $software["status"]]) ?>
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
                        <?= $this->renderPartial("layouts/postbox-mod-tools", ["idPost" => $software["idPost"], "modTools" => $softwareModTools]) ?>
                    </div>
                </aside>
            <?php endif; ?>

        <?php else: ?>
            <div class="regular">
                <p class="regular__text">Fiche vide !</p>
            </div>
        <?php endif ?>

    </article>

    <div class="content__subcontent prominent">
        <h2 class="prominent__text">Ancres</h2>
    </div>

    {{ notification }}

    <?php if (isset($anchors)): ?>

        <?php foreach ($anchors as $anchor): ?>

            <article class="content__subcontent">

                <div class="prominent">
                    <h3 class="prominent__text">
                        <a class="anchor" href="<?= $anchor["anchorUrl"] ?>"
                           target="_blank"><?= $anchor["anchorUrl"] ?></a>
                    </h3>

                    <div class="postbox postbox-status postbox-status-<?= $anchor["idPost"] ?>">
                        <?= $this->renderPartial("layouts/postbox-status", ["postStatus" => $anchor["status"]]) ?>
                    </div>
                </div>

                <div class="regular">
                    <p class="regular__text"><?= $anchor["anchorContent"] ?></p>
                </div>

                <?php if (isset($anchor["anchorModTools"])): ?>
                    <aside class="menu">
                        <div class="menu__submenu menu__submenu-h menu__submenu-r postbox postbox-mod-tools-<?= $anchor["idPost"] ?>">
                            <?= $this->renderPartial("layouts/postbox-mod-tools", ["idPost" => $anchor["idPost"], "modTools" => $anchor["anchorModTools"]]) ?>
                        </div>
                    </aside>
                <?php endif; ?>

            </article>

        <?php endforeach; ?>

    <?php else: ?>

        <article class="content__subcontent">

            <div class="regular">
                <p class="regular__text">Aucunes ancres associées.</p>
            </div>

        </article>

    <?php endif ?>

</section>
