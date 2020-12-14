<?php

namespace TaskForce\widgets\dropzone;

use yii\web\AssetBundle;

class DropzoneWidgetBundle extends AssetBundle {
    public $sourcePath = __DIR__ . '/assets';
    public $css = ['dropzone.css'];
    public $js = ['dropzone.js',
    'dropzone.init.js'];
    public $depends = [
        'yii\web\YiiAsset',
    ];

}
