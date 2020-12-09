<div class="user__card-photo">
    <h3 class="content-view__h3">Фото работ</h3>
    <?= /** @var array $works */
    (!$works) ? '-' : '' ?>
    <?php
    /** @var string $userId */

    foreach ($works as $key => $work): ?>
        <a href="#"><img src="/uploads/works/<?=
            $userId . '/' . $work['filename'] ?>" width="85" height="86"
                         alt="Фото работы"></a>
    <?php
    endforeach; ?>
</div>