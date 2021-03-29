<?php

/** @var array $flashes */
foreach ($flashes as $type => $flash): ?>
    <section class="modal enter-form form-modal landing-task" style="display: block;">
            <h3>
                <?php
                echo $flash; ?>
            </h3>
        <button class="form-modal-close" type="button">Закрыть</button>
    </section>
<?
endforeach; ?>
