<section class="content">
    <div class="content__subcontent prominent">
        <h2 class="prominent__text">Logiciel</h2>
        <?php if (isset($moderatorTools)): ?>
            <a class="prominent__icon icon-<?= $moderatorTools["addArticle"]["icon"] ?>" href="<?= $moderatorTools["addArticle"]["href"] ?>"
                  title="<?= $moderatorTools["addArticle"]["title"] ?>"></a>
        <?php endif; ?>
    </div>
    <article class="content__subcontent">
        <?php if (isset($software)): ?>
            <div class="prominent">
                <h3 class="prominent__text">
                    <span class="prominent__text"><?= $software["softwareName"] ?></span>
                </h3>
                <?php foreach ($software["status"] as $status): ?>
                    <span class="prominent__icon icon-<?= $status["icon"] ?>"
                          title="<?= $status["title"] ?>"></span>
                <?php endforeach; ?>
            </div>
            <div class="regular">
                <?php foreach($software["summary"] as $summaryPart) : ?>
                    <p class="regular__text"><?= $summaryPart ?></p>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="regular">
                <p class="regular__text">Fiche vide !</p>
            </div>
        <?php endif ?>
    </article>
</section>
