<?php
namespace app\modules\question\assets;

use yii\web\AssetBundle;

class QuestionAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/question/web';
    public $css = [
        'css/question.css'
    ];
    public $js = [
        'js/question.js'
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];
}
