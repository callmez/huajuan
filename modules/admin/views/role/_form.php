<?php
use yii\rbac\Item;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ListView;
use yii\bootstrap\ActiveForm;
use yii\data\ArrayDataProvider;
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
?>

<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
    'fieldConfig' => [
        'template' => "{label}\n{beginWrapper}\n{input}\n{endWrapper}<div class='col-sm-6'>\n{hint}\n{error}</div>",
        'horizontalCssClasses' => [
            'label' => 'col-sm-2',
            'offset' => 'col-sm-offset-2',
            'wrapper' => 'col-sm-4',
            'error' => '',
            'hint' => '',
        ],
    ]
]) ?>
    <?= $form->field($authItemForm, 'name') ?>
    <?= $form->field($authItemForm, 'description') ?>
    <?= $form->field($authItemForm, 'ruleName') ?>
    <?= $form->field($authItemForm, 'data')->textarea() ?>

    <?php if ($authItemForm->type == Item::TYPE_ROLE): //角色专用 ?>
        <div class="form-group">
            <label class="control-label col-sm-2">子角色</label>
            <div class="col-sm-10">
                <?= GridView::widget([
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
                            'value' => function ($data) use ($authItemForm, $children) {
                                return Html::checkbox(Html::getInputName($authItemForm, "children[]"), isset($children[$data->name]), [
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
                                return Html::a($data->name, ['view', 'name' => $data->name]);
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
                                        'class' => 'col-sm-4'
                                    ],
                                    'itemView' => function ($model, $key, $index, $widget) {
                                        return Html::label(Html::checkbox($model->name, true, [
                                            'disabled' => true
                                        ]) . ' ' . $model->description ?: $model->name);
                                    },
                                    'layout' => '<div class="row">{items}</div>',
                                ]);
                            },
                            'footer' => '<div id="extendPermissions" class="row"></div>'
                        ]
                    ]
                ]) ?>
            </div>
        </div>
    <?php endif ?>
    <div class="form-group">
        <label class="control-label col-sm-2">基本权限</label>
        <div class="col-sm-10">
            <?= ListView::widget([
                'dataProvider' => $permissionsDataProvider,
                'itemOptions' => [
                    'class' => 'col-sm-3 col-xs-6'
                ],
                'itemView' => function ($model, $key, $index, $widget) use ($authItemForm, $children) {
                    $checkbox = Html::checkbox(Html::getInputName($authItemForm, "children[]"), isset($children[$model->name]), [
                        'value' => $model->name
                    ]);
                    return Html::label($checkbox . ' ' . ($model->description ?: $model->name), null, [
                        'class' => 'checkbox',
                    ]);
                },
                'layout' => '<div class="row">{items}</div>',
            ]) ?>
        </div>
    </div>
    <div class="form-group field-authitemform-data">
        <div class="col-sm-4 col-sm-offset-2">
            <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
<?php ActiveForm::end() ?>