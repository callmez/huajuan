<?php
namespace app\modules\admin\assets;

use yii\web\AssetBundle;

class AdminAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/admin/web';
    public $css = [
		'css/admin.css'
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'app\modules\admin\assets\AdminLteAsset'
    ];
}