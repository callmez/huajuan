<?php

use yii\db\Schema;
use yii\db\Migration;
use yii\helpers\Console;
use app\components\MigrationTrait;
use app\modules\admin\components\Menu;

/**
 * 初始化admin模块数据
 */
class m150113_144206_initAdmin extends Migration
{
    use MigrationTrait;
    public function up()
    {
        $this->initMenu();
    }

    public function down()
    {}

    /**
     * 初始化后台菜单
     */
    public function initMenu()
    {
        Console::output('注册后台基本菜单 ....');

        //系统设置
        Menu::set('system', [
            'label' => '系统设置',
            'url' => ['/admin/system/index'],
            'icon' => 'fa-gears',
            'priority' => 50
        ]);

        Console::output('注册后台基本菜单完成 ....');
    }
}
