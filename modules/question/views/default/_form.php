<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\assets\PageDownAsset;
PageDownAsset::register($this);
$this->registerJs("
    var commentConverter = Markdown.getSanitizingConverter();
        commentEditor = new Markdown.Editor(commentConverter);
        commentEditor.run();
");
?>

<div class="question-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'subject',[
        'template' => "{input}\n{hint}\n{error}"
    ])->textInput([
        'maxlength' => 255,
        'class' => 'form-control input-lg',
        'placeholder' => '请用一句话描述您的问题'
    ]) ?>

    <?= $form->field($model, 'content', [
        'template' => "<div id=\"wmd-button-bar\"></div>{input}\n{hint}\n{error}<div id=\"wmd-preview\"></div>",
        'selectors' => [
            'input' => '#wmd-input'
        ]
    ])->textarea([
        'id' => 'wmd-input',
        'class' => 'form-control input-lg',
        'placeholder' => '在这里详细描述您的问题,支持Markdown语法',
        'rows' => 10
    ]) ?>

    <div class="form-group text-right">
        <?= Html::submitButton('发表问题', ['class' => 'btn btn-primary btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
