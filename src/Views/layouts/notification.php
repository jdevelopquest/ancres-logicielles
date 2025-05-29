<?php if (isset($success)): ?>

    <div class="prominent prominent-success">
        <h3 class="prominent__text">Succès de l'opération</h3>
    </div>

    <div class="regular">
        <?php foreach ($success as $message) : ?>
            <p class="regular__text">
                <?= $message ?>
            </p>
        <?php endforeach; ?>
    </div>

<?php endif; ?>

<?php if (isset($error)): ?>

    <div class="prominent prominent-error">
        <h3 class="prominent__text">Échec de l'opération</h3>
    </div>

    <div class="regular">
        <?php foreach ($error as $message) : ?>
            <p class="regular__text">
                <?= $message ?>
            </p>
        <?php endforeach; ?>
    </div>

<?php endif; ?>