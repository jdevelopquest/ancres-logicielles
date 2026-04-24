<section class="content">
    <div class="content__subcontent prominent">
        <h2 class="prominent__text">Ajouter une fiche logicielle</h2>
    </div>

    {{ notification }}

    <?php if (!isset($success)): ?>
        <form class="form content__subcontent"
              action="index.php?ctr=posts&act=addSoftware" method="post" name="addSoftware" id="addSoftware">

            <div class="form__subform form__subform-v regular">
                <label class="form__item regular__text" for="softwareName">Nom du logiciel</label>
                <input class="form__item regular__text" required
                       name="softwareName"
                       type="text"
                       id="softwareName"
                       minlength="1"
                       maxlength="100"
                       pattern="^[\p{L}\p{N}\s\p{P}\p{S}]{1,100}$"
                       value="<?= $softwareName ?? "" ?>"/>
            </div>

            <div class="form__subform form__subform-v regular">
                <label class="form__item regular__text" for="softwareSummary">Description du logiciel</label>
                <textarea class="form__item regular__text" required
                          name="softwareSummary"
                          id="softwareSummary"
                          minlength="10"
                          maxlength="2000"><?= $softwareSummary ?? null ?></textarea>
            </div>

            <div class="form__subform prominent">
                <input class="form__item form__item-clickable prominent__text" type="submit" value="Enregistrer">
            </div>
        </form>
    <?php endif; ?>
</section>

