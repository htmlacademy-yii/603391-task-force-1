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
            'enableStrictParsing' => true,
            'rules' => [
                'executors' => 'users/index',
                'tasks' => 'tasks/index'
                ]
            ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ]


    ],
];
