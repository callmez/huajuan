<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\assets\PageDownAsset;
PageDownAsset::register($this);
if (!Yii::$app->user->getIsGuest()) {
    $this->registerJs("
        $('#wmd-input').one('focus', function(){
            var commentConverter = Markdown.getSanitizingConverter();
                commentEditor = new Markdown.Editor(commentConverter);
            commentEditor.run();
            $('#wmd-preview').removeClass('hide');
        });
    ");
}
?>
<div class="answer-form clearfix">
    <h4>我要回答 <?php if (Yii::$app->user->getIsGuest()): ?> <small class="text-warning">(需要登录)</small> <?php endif ?></h4>
    <?php $form = ActiveForm::begin([
        'fieldConfig' => [
            'template' => "{input}\n{hint}\n{error}"
        ]
    ]); ?>
    <?= $form->errorSummary($model, [
        'class' => 'alert alert-danger'
    ]) ?>
    <div class="wmd-panel">
        <div id="wmd-button-bar"></div>
        <?= $form->field($model, 'content', [
            'selectors' => [
                'input' => '#wmd-input'
            ],
        ])->textarea([
            'disabled' => Yii::$app->user->getIsGuest(),
            'id' => 'wmd-input',
            'class' => 'form-control input-lg',
            'rows' => 6
        ]) ?>
    </div>
    <div class="form-group text-right">
        <?= Html::submitButton('提交回答', ['class' => 'btn btn-success']) ?>
    </div>
    <div id="wmd-preview" class="hide"></div>
    <?php ActiveForm::end(); ?>
</div>
