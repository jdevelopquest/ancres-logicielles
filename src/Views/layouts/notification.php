<?php if (isset($success)): ?>

    <div class="prominent prominent-success">
        <h3 class="prominent__text">Succès de l'opération</h3>
    </div>

<?php endif; ?>

<?php if (isset($error)): ?>

    <div class="prominent prominent-error">
        <h3 class="prominent__text">Échec de l'opération</h3>
    </div>

        <div class="regular">
            <p class="regular__text">
                <?= $error ?>
            </p>
        </div>

<?php endif; ?>