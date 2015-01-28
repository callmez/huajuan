<?php
use yii\rbac\Item;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\data\ArrayDataProvider;
use app\modules\admin\widgets\GridView;
use app\modules\admin\widgets\ActiveForm;

if ($type == Item::TYPE_ROLE) {
    $name = '角色';
    $key = 'roles';
} else {
    $name = '权限';
    $key = 'permissions';
}
$this->title = $opeartion . $name;
$this->params['breadcrumbs'] = [
    [
        'url' => ['/admin/rbac'],
        'label' => '角色与权限'
    ],
    [
        'url' => ['/admin/rbac/' . $key],
        'label' => $name . '列表'
    ],
    $this->title
];
$this->params['activeMenu'] = 'rbac/' . $key;
?>

    <div class="box">
        <div class="box-body">
            <?php $form = ActiveForm::begin() ?>
            <div class="page-header"><?= $name ?>属性</div>
            <?= $form->field($authItemForm, 'description') ?>
            <?= $form->field($authItemForm, 'name') ?>
            <?= $form->field($authItemForm, 'ruleName') ?>
            <?= $form->field($authItemForm, 'data')->textarea() ?>

            <?php if ($type == Item::TYPE_ROLE): //角色专用 ?>
                <div class="page-header">子角色</div>
                <?= Html::activeHiddenInput($authChildItemForm, 'child') //当childItem一个都没有选中时的默认提交?>
                <?=
                GridView::widget([
                    'id' => 'childPermissions',
                    'showFooter' => true,
                    'dataProvider' => $rolesDataProvider,
                    'layout' => "<div class=\"grid-view-body table-responsive\">{items}</div>",
                    'columns' => [
                        [
                            'options' => [
                                'width' => 30
                            ],
                            'format' => 'raw',
                            'value' => function ($data) use ($authChildItemForm, $childRoles) {
                                    return Html::checkbox(Html::getInputName($authChildItemForm, "child[{$data->type}][]"), isset($childRoles[$data->name]), [
                                        'value' => $data->name,
                                        'data-key' => 'child-role-checkbox'
                                    ]);
                                }
                        ],
                        [
                            'attribute' => 'description',
                            'options' => [
                                'width' => 150
                            ],
                            'label' => $authItemForm->getAttributeLabel('description')
                        ],
                        [
                            'attribute' => 'name',
                            'options' => [
                                'width' => 120
                            ],
                            'label' => $authItemForm->getAttributeLabel('name'),
                            'format' => 'html',
                            'value' => function ($data) {
                                    return Html::a($data->name, ['/admin/rbac/update-role', 'name' => $data->name]);
                                }
                        ],
                        [
                            'label' => '权限',
                            'format' => 'raw',
                            'value' => function ($data) {
                                    return ListView::widget([
                                        'dataProvider' => new ArrayDataProvider([
                                                'models' => Yii::$app->getAuthManager()->getPermissionsByRole($data->name)
                                            ]),
                                        'itemOptions' => [
                                            'class' => 'col-sm-3'
                                        ],
                                        'itemView' => function ($model, $key, $index, $widget) {
                                                return Html::label(Html::checkbox($model->name, true, [
                                                    'disabled' => true
                                                ]) . ' ' . $model->description ? : $model->name);
                                            },
                                        'layout' => '<div class="row">{items}</div>',
                                    ]);
                                },
                            'footer' => '<div id="extendPermissions" class="row"></div>'
                        ]
                    ]
                ]) ?>
            <?php endif ?>

            <div class="page-header">角色基本权限</div>
            <?=
            ListView::widget([
                'dataProvider' => $permissionsDataProvider,
                'itemOptions' => [
                    'class' => 'col-sm-3 col-xs-6'
                ],
                'itemView' => function ($model, $key, $index, $widget) use ($authChildItemForm, $childPermissions) {
                        $checkbox = Html::checkbox(Html::getInputName($authChildItemForm, "child[{$model->type}][]"), isset($childPermissions[$model->name]), [
                            'value' => $model->name,
                            'data-key' => 'child-role-checkbox'
                        ]);
                        return Html::label($checkbox . ' ' . ($model->description ? : $model->name));
                    },
                'layout' => '<div class="row">{items}</div>',
            ]) ?>

            <?= $form->action(Html::submitButton('提交', ['class' => 'btn btn-primary'])) ?>
            <?php ActiveForm::end() ?>
        </div>
    </div>
<?php
$script = <<<EOF

//继承的权限控制
var extendPermissions = $('#extendPermissions'),
    childRoles = $('[data-key=child-role-checkbox]');
childRoles.on('change', function(){
    var selectChildRoles = childRoles.filter(':checked'),
        keys = {};
        extendPermissions.empty();
    if (selectChildRoles.length) {
        var permissions = selectChildRoles.closest('tr').find('.list-view .row > div').clone().filter(function(index){
            var name = $('input[type=checkbox]', this).attr('name');
            keys[name] || (keys[name] = 0);
            return ++keys[name] <= 1;
        });
        if (permissions.length) {
            return permissions.appendTo(extendPermissions);
        }
    }
    extendPermissions.append('<div class="col-sm-12">没有可继承的权限</div>');
}).eq(0).change();

EOF;
$this->registerJs($script);
