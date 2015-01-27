<?php
use yii\helpers\Html;

$this->title = '提问题';
$this->params['breadcrumbs'][] = $this->title;
$this->context->module->layout = 'column1';
?>
<div class="question-create">

    <h3 class="mb20"><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
        'tagModel' => $tagModel
    ]) ?>

</div>
