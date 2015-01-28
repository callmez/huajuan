<?php
use yii\helpers\Html;
use yii\grid\GridView;
?>
<p><?= Html::a('添加权限', ['create'], ['class' => 'btn btn-primary']) ?></p>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'attribute' => 'name',
            'label' => $authItemForm->getAttributeLabel('name'),
            'format' => 'html',
            'value' => function ($data) {
                return Html::a($data->name, ['update-role', 'name' => $data->name]);
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
            'format' => ['date', 'Y-m-d H:i:s'],
            'options' => [
                'width' => 140
            ]
        ],
        [
            'attribute' => 'updatedAt',
            'label' => $authItemForm->getAttributeLabel('updatedAt'),
            'format' => ['date', 'Y-m-d H:i:s'],
            'options' => [
                'width' => 140
            ]
        ]
    ]
]) ?>