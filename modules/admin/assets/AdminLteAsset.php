<?php
namespace app\modules\admin\assets;

use yii\web\AssetBundle;

class AdminLteAsset extends AssetBundle
{
    public $sourcePath = '@bower/adminlte';
    public $css = [
        'plugins/morris/morris.css',

        'dist/css/AdminLTE.css',
    ];
    public $js = [
        'plugins/morris/morris.js',
        'plugins/iCheck/icheck.js',
        'plugins/slimScroll/jquery.slimscroll.js',
        'dist/js/app.js'
    ];
    public $depends = [
        'yii\bootstrap\BootstrapPluginAsset',
        'app\assets\FontAwesomeAsset',
    ];
}