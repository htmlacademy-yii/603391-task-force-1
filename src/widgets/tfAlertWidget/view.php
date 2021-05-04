<?php
/** @var array $flashes */
foreach ($flashes as $type => $flashItems): ?>
    <section class="modal enter-form form-modal landing-task flash-<?= $type; ?> " style="display: block;">
            <h3>
                <?= $flashItems; ?>
            </h3>
        <button class="form-modal-close" type="button">Закрыть</button>
    </section>
<?php endforeach; ?>
