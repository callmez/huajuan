<?php
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = '角色列表';
$this->params['breadcrumbs'] = [
    '角色与权限',
    $this->title
];
?>
<div class="box">
    <div class="box-body">
        <p><?= Html::a('添加角色', ['add-role'], ['class' => 'btn btn-primary']) ?></p>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'attribute' => 'name',
                    'format' => 'html',
                    'value' => function ($data) {
                            return Html::a($data->name, ['update-role', 'name' => $data->name]);
                        }
                ],
                [
                    'attribute' => 'description',
                ],
                [
                    'attribute' => 'ruleName',
                ],
                [
                    'attribute' => 'data',
                ],
                [
                    'attribute' => 'createdAt',
                    'format' => ['date', 'Y-m-d H:i:s'],
                    'options' => [
                        'width' => 140
                    ]
                ],
                [
                    'attribute' => 'updatedAt',
                    'format' => ['date', 'Y-m-d H:i:s'],
                    'options' => [
                        'width' => 140
                    ]
                ],
            ]
        ]) ?>
    </div>
</div>