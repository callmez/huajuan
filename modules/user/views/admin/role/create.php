<?php

$this->title = '角色创建';
$this->params['breadcrumbs'] = [
    '角色与权限',
    $this->title
];
?>
<?= $this->render('_form', [
    'name' => '角色',
    'childRoles' => [],
    'childPermissions' => [],
    'rolesDataProvider' => $rolesDataProvider,
    'permissionsDataProvider' => $permissionsDataProvider,
    'authItemForm' => $authItemForm,
    'authChildItemForm' => $authChildItemForm,
]) ?>
