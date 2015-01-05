<?php
namespace app\assets;

use yii\web\AssetBundle;

class FontAwesomeAsset extends AssetBundle
{
    public $sourcePath = '@bower/fontawesome';
    public $css = [
        'css/font-awesome.css',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
}