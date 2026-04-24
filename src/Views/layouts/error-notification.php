<div class="prominent prominent-error">
    <h3 class="prominent__text">Échec de l'opération</h3>
</div>

<?php if (isset($error)): ?>
    <div class="regular">
        <p class="regular__text">
            <?= $error ?>
        </p>
    </div>
<?php endif; ?>