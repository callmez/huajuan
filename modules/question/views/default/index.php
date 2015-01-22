<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ListView;

$this->title = 'Questions';
$sort = Yii::$app->request->getQueryParam('sort');
?>
<div class="question-index">
    <div class="main-panel">

    </div>

    <ul class="nav nav-tabs mb10">
        <?php foreach($sorts as $key => $name): ?>
            <li <?php if ($sort == $key || ((empty($sort) && $key == 'newest'))): ?>class="active"<?php endif ?>>
                <a href="<?= Url::current(['sort' => $key]) ?>"><?= $name ?></a>
            </li>
        <?php endforeach ?>
    </ul>
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'summary' => false,
        'itemView' => '_questionList',
        'layout' => "{summary}\n{items}\n<div class=\"text-center\">{pager}</div>"
    ]) ?>
</div>
