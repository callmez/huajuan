<?php
$this->title = '角色创建';
$this->params['breadcrumbs'] = [
    '角色与权限',
    ['label' => '角色列表', 'url' => ['index']],
    $this->title
];
?>
<?= $this->render('../role/_form', [
    'name' => '角色',
    'children' => [],
    'authItemForm' => $authItemForm,
    'permissionsDataProvider' => $permissionsDataProvider
]) ?>
