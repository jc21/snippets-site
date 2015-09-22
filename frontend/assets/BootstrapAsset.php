<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class BootstrapAsset extends AssetBundle
{
    public $sourcePath = '@bower/bootstrap/dist';
    public $js = [
        'js/bootstrap.min.js',
    ];

    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
}