<?php
use Yii;
use yii\widgets\ListView;

$this->title = $model->subject;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="question-view">
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
    <?= $this->render('_answerForm', [
        'model' => $answer
    ]) ?>
</div>