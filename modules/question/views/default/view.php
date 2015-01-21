<?php
use \Yii;
use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $model app\modules\forum\models\Topic */
//$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Questions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="topic-view">
    <?= $this->render('_question', [
        'model' => $model
    ]) ?>
    <?= ListView::widget([
        'dataProvider' => $answerDataProvider,
        'itemView' => '_question',
        'summary' => false,
        'emptyText' => '暂时还没有新的回答',
        'emptyTextOptions' => [
            'class' => 'text-center'
        ]
    ]) ?>
    <?php if (!Yii::$app->user->getIsGuest()): ?>
        <?= $this->render('_answerForm', [
            'model' => $answer
        ]) ?>
    <?php endif ?>
</div>