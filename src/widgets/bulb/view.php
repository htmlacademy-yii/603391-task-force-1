<?php

use yii\helpers\Url;

/** @var array $eventsCount */

const EVENT_CLASS = [
    1 => 'message',
    2 => 'executor',
    3 => 'close',
];

if (count($eventsCount)) {
    echo '<div class="header__lightbulb header__lightbulb-white"></div>';
} else {
    echo '<div class="header__lightbulb"></div>';
}
?>
<div class="lightbulb__pop-up">
    <h3>Новые события</h3>
    <?php
    foreach ($eventsCount as $event): ?>
        <p class="lightbulb__new-task lightbulb__new-task--<?= EVENT_CLASS[$event['notification_id']] ?>">
            <?= $event['info'] ?> &nbsp;
            <a href="
            <?php
            if (in_array($event['notification_id'], [1, 2])) {
                echo Url::to(['tasks/view', 'id' => $event['task_id']]);
            } else {
                echo Url::to(['users/view', 'id' => $event['user_id']]);
            }
            ?>
            " class="link-regular">
                «<?= $event['title'] ?>»
            </a>
        </p>
    <?
    endforeach; ?>
</div>