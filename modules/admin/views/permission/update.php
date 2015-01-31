<?php

$this->title = '权限修改';
$this->params['breadcrumbs'] = [
    '角色与权限',
    $this->title
];
?>
<?= $this->render('../role/_form', [
    'name' => '权限',
    'children' => $children,
    'authItemForm' => $authItemForm,
    'permissionsDataProvider' => $permissionsDataProvider
]) ?>
