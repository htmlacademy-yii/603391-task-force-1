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
                'users/view/<id:\d+>' => 'users/view',
                'users/<sortType:\d+>' => 'users/index',
                'address/location/<search:\d+>' => 'address/location',
                '/' => 'landing/index',
                ]
            ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ]


    ],
];
