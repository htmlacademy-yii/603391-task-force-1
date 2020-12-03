<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
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
                ['class' => 'yii\rest\UrlRule', 'controller' => 'api/messages']
                ]
            ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ]


    ],
];
