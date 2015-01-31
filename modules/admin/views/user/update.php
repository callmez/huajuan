<?php

use yii\helpers\Html;

$this->title = '修改用户';
$this->params['breadcrumbs'][] = ['label' => '用户管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改用户';
?>
<?= $this->render('_form', [
    'model' => $model,
]) ?>
