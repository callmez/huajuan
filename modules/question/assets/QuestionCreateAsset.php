<?php
namespace app\modules\question\assets;

use yii\web\AssetBundle;

class QuestionCreateAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/question/web';
    public $js = [
        'js/question.create.js'
    ];
    public $depends = [
        'app\modules\question\assets\QuestionAsset',
        'app\assets\PageDownAsset',
        'app\assets\SelectizeAsset'
    ];
}
