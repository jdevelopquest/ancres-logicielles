<section class="content">
    <div class="content__subcontent prominent">
        <h2 class="prominent__text">Connexion</h2>
    </div>

    {{ notification }}

    <?php if (!isset($login_success)): ?>
        <form class="form content__subcontent"
              action="index.php?ctr=accounts&act=login" method="post" name="login" id="login">

            <div class="form__subform form__subform-v regular">
                <label class="form__item regular__text" for="accountUsername">Pseudo</label>
                <input class="form__item regular__text" required
                       name="accountUsername"
                       type="text"
                       id="accountUsername"
                       minlength="3"
                       maxlength="200"
                       pattern="^[a-zA-Z0-9]{3,200}$"
                       value="<?= $accountUsername ?? "" ?>"/>
            </div>

            <div class="form__subform form__subform-v regular">
                <label class="form__item regular__text" for="accountPassword">Mot de passe</label>
                <input class="form__item regular__text" required
                       name="accountPassword"
                       type="password"
                       id="accountPassword"
                       minlength="12"
                       maxlength="255"
                       pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[a-zA-Z0-9]{12,}$"
                       value=""/>
            </div>

            <div class="form__subform prominent">
                <input class="form__item form__item-clickable prominent__text" type="submit" value="Connexion">
            </div>
        </form>
    <?php endif; ?>
</section>
