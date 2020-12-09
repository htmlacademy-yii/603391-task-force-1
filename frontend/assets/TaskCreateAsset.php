<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class TaskCreateAsset extends AssetBundle
{
    public $js = [
        'js/autoComplete.min.js',
        'js/autoComplete.init.js',
        'js/messenger.js'
    ];
    public $css = [
        'css/js-plugin.css',
    ];
}