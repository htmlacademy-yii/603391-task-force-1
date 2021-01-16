<?php

use yii\web\JsonParser;
use yii\web\Response;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'parsers' => [
                'application/json' => [
                    'class' => JsonParser::class,
                 ],
                ],
        ],
        'response' => [
            'formatters' => [
                Response::FORMAT_JSON => [
                    'class' => 'yii\web\JsonResponseFormatter',
                    'prettyPrint' => YII_DEBUG, // используем "pretty" в режиме отладки
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                ],
            ],
        ],
        'user' => [
            'identityClass' => 'frontend\models\User',
            'enableAutoLogin' => true,
            'enableSession' => true,
            'identityCookie' => [
                'name' => '_identity',
                'path' => '/',
                'httpOnly' => true,
                'domain' => $params['mainURL'],
                'sameSite' => 'None',
                'secure' => true
            ],
            'loginUrl' => array('landing/index'),
        ],
        'session' => [
            'name' => 'advanced',
            'cookieParams' => [
                'domain' => $params['mainURL'],
                'httpOnly' => true,
                'secure' => true,
                'path' => '/',
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
            'urlManager' => [
                'enablePrettyUrl' => true,
                'showScriptName' => false,
                'enableStrictParsing' => false,
                'rules' => [
                    'users' => 'users/index',
                    'tasks' => 'tasks/index',
                    'task' => '/task/create',
                    'tasks/view/<id:\d+>' => 'tasks/view',
                    'site/file/<id:\d+>' => 'site/file',
                    'site/city/<cityId:\d+>' => 'site/city',
                    'users/view/<id:\d+>' => 'users/view',
                    'users/<sortType:\d+>' => 'users/index',
                    'my-list/<filter:\d+>' => 'my-list/index',
                    'users/bookmark/<userId:\d+>' => 'users/bookmark',
                    'address/location/<search:\d+>' => 'address/location',
                    '/' => 'landing/index',
                ]
            ],
    ],
    'params' => $params,
];
