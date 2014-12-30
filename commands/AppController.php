<?php
namespace app\commands;

use yii\console\Controller;
use yii\helpers\Console;

/**
 * 项目辅助命令
 *
 * @package app\commands
 */
class AppController extends Controller
{

    public function actionIndex()
    {
    }

    /**
     * 项目安装 当代码第一次初始化后执行此命令可引导安装项目必要设置
     */
    public function actionInstall()
    {
    }
}