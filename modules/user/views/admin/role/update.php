<?php

$this->title = '角色修改';
$this->params['breadcrumbs'] = [
    '角色与权限',
    $this->title
];
?>
<?= $this->render('_form', [
    'name' => '角色',
    'childRoles' => $childRoles,
    'childPermissions' => $childPermissions,
    'rolesDataProvider' => $rolesDataProvider,
    'permissionsDataProvider' => $permissionsDataProvider,
    'authItemForm' => $authItemForm,
    'authChildItemForm' => $authChildItemForm,
]) ?>
