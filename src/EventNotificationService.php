<?php

namespace TaskForce;

use frontend\models\Event;
use Yii;

class EventNotificationService
{
    /**
     * @param Event $event
     */
    public static function sendEmail(Event $event): void
    {
        Yii::$app->mailer->compose()
            ->setFrom(Yii::$app->params['senderEmail'])
            ->setTo($event->user->email)
            ->setSubject('Уведомление с сайта ' . Yii::$app->params['AppName'])
            ->setTextBody($event->info)
            ->setHtmlBody(sprintf('<b>%s</b>', $event->info))
            ->send();
    }
}