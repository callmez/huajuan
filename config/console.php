<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests');

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'gii'],
    'controllerNamespace' => 'app\commands',
    'modules' => require(__DIR__ . '/modules.php'),
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'config' => 'callmez\config\adapters\DbConfig'
    ],
    'params' => require(__DIR__ . '/params.php'),
];

$config['modules']['gii'] = 'yii\gii\Module';

// 项目刚创建时db.php是没有的.所以不需要加载
if (file_exists(__DIR__ . '/db.php')) {
    $config['components']['db'] = $db = require(__DIR__ . '/db.php');
}
return $config;
