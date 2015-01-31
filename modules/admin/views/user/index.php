<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = '用户管理';
$this->params['breadcrumbs'][] = $this->title;
?>
    <?= $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('创建用户', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'id',
                'options' => [
                    'width' => 45
                ]
            ],
            [
                'attribute' => 'username',
                'format' => 'html',
                'value' => function ($data) {
                    return Html::a($data->username, ['update', 'id' => $data->id]);
                }
            ],
            [
                'attribute' => 'role',
                'label' => '角色',
                'format' => 'html',
                'value' => function ($data) {
                    $rolesLink = array_map(function($role) {
                        return Html::a($role->description, ['/user/admin/role/update', 'id' => $role->name]);
                    }, Yii::$app->getAuthManager()->getRolesByUser($data->id));
                    return implode(', ', $rolesLink);
                }
            ],
            [
                'attribute' => 'email',
                'format' => 'email',
                'options' => [
                    'width' => 200
                ]
            ],
            [
                'attribute' => 'status',
                'options' => [
                    'width' => 55
                ]
            ],
            [
                'attribute' => 'created_at',
                'format' => 'datetime',
                'options' => [
                    'width' => 180
                ]
            ],
            [
                'attribute' => 'updated_at',
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
        ],
    ]); ?>
</div>
