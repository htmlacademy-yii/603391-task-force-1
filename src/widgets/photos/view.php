<div class="user__card-photo">
    <h3 class="content-view__h3">Фото работ</h3>
    <?= /** @var array $works */
    (!$works) ? '-' : '' ?>
    <?php
    /** @var string $userId */

    foreach ($works as $key => $work): ?>
        <a href="/uploads/works/<?=
        $userId . '/' . $work['generated_name'] ?>"   target="_blank" ><img src="/uploads/works/<?=
            $userId . '/' . $work['generated_name'] ?>" width="85" height="86"
                         alt="Фото работы"></a>
    <?php
    endforeach; ?>
</div>