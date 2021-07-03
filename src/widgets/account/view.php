<?php

use yii\helpers\Url;

/** @var object $loggedUser */
/** @var string $userAvatar */

?>
<div class="header__account">
    <a class="header__account-photo">
        <img src="<?=
        Url::base() . '/uploads/avatars/' . $userAvatar ?>"
             width="43" height="44"
             alt="Аватар пользователя">
    </a>
    <span class="header__account-name">
                            <?= strip_tags($loggedUser->name) ?>
                        </span>
</div>

<div class="account__pop-up">
    <ul class="account__pop-up-list">
        <li>
            <a href="<?= Url::to("@web/my-list/index") ?>">Мои задания</a>
        </li>
        <li>
            <a href="<?= Url::to("@web/account/index") ?>">Настройки</a>
        </li>
        <li>
            <a href="<?= Url::to('@web/user/logout') ?>">Выход</a>
        </li>
    </ul>
</div>