<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Landing asset bundle.
 */
class LandingAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/normalize.css',
        'css/style.css',
        'css/task-force.css',
    ];
    public $js = [
        'js/main.js'];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
