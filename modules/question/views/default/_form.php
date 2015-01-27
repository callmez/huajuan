<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use app\modules\question\assets\QuestionCreateAsset;
QuestionCreateAsset::register($this);
?>
<div class="question-form">
    <?php $form = ActiveForm::begin([
        'fieldConfig' => [
            'template' => "{input}\n{hint}\n{error}",
            'inputOptions' => [
                'class' => 'form-control input-lg',
            ]
        ]
    ]); ?>
    <?= $form->field($model, 'subject')->textInput([
        'maxlength' => 255,
        'placeholder' => '请用一句话描述您的问题'
    ]) ?>
    <?= Html::activeHiddenInput($model, 'tags', [
        'placeholder' => '为该问题添加标签,例如输入: Yii2',
        'data-max-length' => 5 // TODO 后台控制最大标签数
    ]) ?>
    <?= $form->field($model, 'content', [
        'template' => "<div id=\"wmd-button-bar\"></div>{input}\n{hint}\n{error}<div id=\"wmd-preview\"></div>",
        'selectors' => [
            'input' => '#wmd-input'
        ]
    ])->textarea([
        'id' => 'wmd-input',
        'placeholder' => '在这里详细描述您的问题,支持Markdown语法',
        'rows' => 10
    ]) ?>
    <div class="form-group text-right">
        <?= Html::submitButton('发表问题', ['class' => 'btn btn-primary btn-lg']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php $form = ActiveForm::begin([
    'id' => 'tagForm',
    'action' => ['/tag/default/create']
]) ?>
<?php Modal::begin([
    'header' => '<h4>添加标签</h4>',
    'footer' => Html::button('取消', ['class' => 'btn btn-default', 'data-dismiss' => 'modal']) . ' ' . Html::submitButton('确定', ['class' => 'btn btn-primary']),
]) ?>

        <?= $form->field($tagModel, 'name', [
            'enableAjaxValidation' => true,
        ])->textInput([
            'readonly' => true
        ]) ?>
        <?= $form->field($tagModel, 'description')->textarea([
            'rows' => 5,
            'placeholder' => '请对该标签补充一些详细描述, 以供他人参考'
        ]) ?>
<?php Modal::end() ?>
<?php ActiveForm::end() ?>