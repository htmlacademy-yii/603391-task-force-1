<?php
/* @var $this yii\web\View */

$this->title = 'TaskForce - Исполнители';
?>

<section class="user__search">
    <div class="user__search-link">
        <p>Сортировать по:</p>
        <ul class="user__search-list">
            <li class="user__search-item user__search-item--current">
                <a href="#" class="link-regular">Рейтингу</a>
            </li>
            <li class="user__search-item">
                <a href="#" class="link-regular">Числу заказов</a>
            </li>
            <li class="user__search-item">
                <a href="#" class="link-regular">Популярности</a>
            </li>
        </ul>
    </div>
    <?php
    foreach ($models as $user): ?>
    <div class="content-view__feedback-card user__search-wrapper">
        <div class="feedback-card__top">
            <div class="user__search-icon">
                <a href="#"><img src="./img/<?= $user['avatar'] ?>" width="65" height="65"></a>
                <span><?= $user['countTasks'] ?> заданий</span>
                <span><?= $user['countReplies'] ?> отзывов</span>
            </div>

            <div class="feedback-card__top--name user__search-card">
                <p class="link-name"><a href="#" class="link-regular"><?= $user['name'] ?></a></p>

                <?= str_repeat('<span></span>', $user['rate']); ?>
                <?= str_repeat('<span class="star-disabled"></span>', 5 - $user['rate']); ?>
                <b><?= $user['rate'] ?></b>
                <p class="user__search-content">
                    <?= $user['about'] ?>
                </p>
            </div>
            <span class="new-task__time">Был на сайте </br> <?= $user['afterTime'] ?> назад</span>
        </div>
        <div class="link-specialization user__search-link--bottom">
            <?php
            if (isset($user['categories'])) {
                foreach ($user['categories'] as $key => $item): ?>
                    <a href="#" class="link-regular"><?= $item['name'] ?></a>
                <?php endforeach;
            } ?>
        </div>
        <?php endforeach ?>
    </div>
</section>
<section class="search-task">
    <div class="search-task__wrapper">
        <form class="search-task__form" name="users" method="post" action="#">
            <fieldset class="search-task__categories">
                <legend>Категории</legend>
                <input class="visually-hidden checkbox__input" id="101" type="checkbox" name="" value="" checked
                       disabled>
                <label for="101">Курьерские услуги </label>
                <input class="visually-hidden checkbox__input" id="102" type="checkbox" name="" value="" checked>
                <label for="102">Грузоперевозки </label>
                <input class="visually-hidden checkbox__input" id="103" type="checkbox" name="" value="">
                <label for="103">Переводы </label>
                <input class="visually-hidden checkbox__input" id="104" type="checkbox" name="" value="">
                <label for="104">Строительство и ремонт </label>
                <input class="visually-hidden checkbox__input" id="105" type="checkbox" name="" value="">
                <label for="105">Выгул животных </label>
            </fieldset>
            <fieldset class="search-task__categories">
                <legend>Дополнительно</legend>
                <input class="visually-hidden checkbox__input" id="106" type="checkbox" name="" value="" disabled>
                <label for="106">Сейчас свободен</label>
                <input class="visually-hidden checkbox__input" id="107" type="checkbox" name="" value="" checked>
                <label for="107">Сейчас онлайн</label>
                <input class="visually-hidden checkbox__input" id="108" type="checkbox" name="" value="" checked>
                <label for="108">Есть отзывы</label>
            </fieldset>
            <label class="search-task__name" for="109">Поиск по названию</label>
            <input class="input-middle input" id="109" type="search" name="q" placeholder="">
            <button class="button" type="submit">Искать</button>
        </form>
    </div>
</section>



