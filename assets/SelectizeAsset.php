<?php
namespace app\assets;

use yii\web\AssetBundle;

class SelectizeAsset extends AssetBundle
{
    public $sourcePath = '@bower/selectize/dist';
    public $js = [
        'js/standalone/selectize.js'
    ];
    public $css = [
        'css/selectize.css'
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];
}