<?php

$this->title = '角色修改';
$this->params['breadcrumbs'] = [
    '角色与权限',
    $this->title
];
?>
<?= $this->render('_form', [
    'name' => '角色',
    'children' => $children,
    'authItemForm' => $authItemForm,
    'rolesDataProvider' => $rolesDataProvider,
    'permissionsDataProvider' => $permissionsDataProvider
]) ?>
