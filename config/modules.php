<?php
$modules = [
    // 用户模块
    'user' => 'app\modules\user\Module',

    // 问答
    'question' => [
        'class' => 'app\modules\question\Module',
    ],

    // 后台
    'admin' => 'app\modules\admin\Module',

];

return $modules;