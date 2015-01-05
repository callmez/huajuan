<?php
namespace app\modules\admin\assets;

use yii\web\AssetBundle;

class AdminLteAsset extends AssetBundle
{
    public $sourcePath = '@bower/adminlte';
    public $css = [
        'css/morris/morris.css',
        'css/AdminLTE.css',
    ];
    public $js = [
        'js/AdminLTE/app.js'
    ];
    public $depends = [
    'yii\bootstrap\BootstrapAsset',
    'yii\bootstrap\BootstrapPluginAsset',
    'app\assets\FontAwesomeAsset',
    'app\modules\admin\assets\InoIconsAsset'
];
}