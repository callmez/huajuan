<?php

$this->title = '权限创建';
$this->params['breadcrumbs'] = [
    '角色与权限',
    $this->title
];
?>
<?= $this->render('../permission/_form', [
    'model' => $model,
]) ?>
