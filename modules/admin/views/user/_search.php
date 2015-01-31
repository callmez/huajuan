<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    'fieldConfig' => [
        'template' => "<div class=\"input-group\">{label}\n{input}\n{hint}\n{error}</div>",
        'labelOptions' => [
            'class' => 'input-group-addon'
        ],
        'options' => [
            'class' => 'form-group col-sm-4'
        ]
    ]
]); ?>
<div class="row">
    <?= $form->field($model, 'id') ?>
    <?= $form->field($model, 'username') ?>
    <?= $form->field($model, 'email') ?>
</div>
<div class="form-group">
    <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end(); ?>
