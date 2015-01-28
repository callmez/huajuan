<?php
use yii\helpers\Html;
use app\modules\admin\widgets\GridView;

?>
<div class="box">
    <div class="box-body">
        <?= GridView::widget([
            'dataProvider' => $permissionsProvider,
            'button' => Html::a('添加权限', ['add-permission'], ['class' => 'btn btn-primary pull-right']),
            'columns' => [
                [
                    'attribute' => 'name',
                    'label' => '角色名',
                    'format' => 'html',
                    'value' => function ($data) {
                            return Html::a($data->name, ['update-permission', 'name' => $data->name]);
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
                ],
            ]
        ]) ?>
    </div>
</div>