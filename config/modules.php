<?php
$modules = [
    // 用户
    'user' => 'app\modules\user\Module',
    // 标签
    'tag' => [
        'class' => 'app\modules\tag\Module',
    ],
    // 问答
    'question' => [
        'class' => 'app\modules\question\Module',
    ],
    // 后台
    'admin' => 'app\modules\admin\Module',
];

return $modules;