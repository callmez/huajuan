<?php
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = '角色列表';
$this->params['breadcrumbs'] = [
    '角色与权限',
    $this->title
];
?>
<p><?= Html::a('添加角色', ['create'], ['class' => 'btn btn-primary']) ?></p>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'attribute' => 'name',
            'label' => $authItemForm->getAttributeLabel('name'),
            'format' => 'html',
            'value' => function ($data) {
                    return Html::a($data->name, ['update', 'id' => $data->name]);
                }
        ],
        [
            'attribute' => 'description',
            'label' => $authItemForm->getAttributeLabel('description'),
        ],
        [
            'attribute' => 'ruleName',
            'label' => $authItemForm->getAttributeLabel('ruleName'),
        ],
        [
            'attribute' => 'data',
            'label' => $authItemForm->getAttributeLabel('data'),
        ],
        [
            'attribute' => 'createdAt',
            'label' => $authItemForm->getAttributeLabel('createdAt'),
            'format' => 'datetime',
            'options' => [
                'width' => 180
            ]
        ],
        [
            'attribute' => 'updatedAt',
            'label' => $authItemForm->getAttributeLabel('updatedAt'),
            'format' => 'datetime',
            'options' => [
                'width' => 180
            ]
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update} {delete}',
            'options' => [
                'width' => 50
            ]
        ],
    ]
]) ?>