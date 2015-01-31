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
        $this->initAdminMenu();
        $this->initUserMenu();
    }

    public function down()
    {}

    /**
     * 初始化后台基本菜单
     */
    public function initAdminMenu()
    {
        Console::output('注册后台基本菜单 ....');

        //系统设置
        Menu::set('home', [
            'label' => '首页',
            'url' => ['/admin/default/index'],
            'icon' => 'fa-home',
            'priority' => 50
        ]);

        //系统设置
        Menu::set('system', [
            'label' => '系统设置',
            'url' => ['/admin/system/index'],
            'icon' => 'fa-gears',
            'priority' => 200
        ]);

        Console::output('注册后台基本菜单完成 ....');
    }

    /**
     * 初始化后台用户菜单
     */
    public function initUserMenu()
    {
        Console::output('注册用户模块后台管理菜单 ....');

        //用户
        Menu::set('user', [
            'label' => '用户管理',
            'url' => ['/admin/user/index'],
            'icon' => 'fa-user',
            'priority' => 100
        ]);

        //rbac
        Menu::set('rbac', [
            'label' => '角色权限',
            'icon' => 'fa-group',
            'priority' => 100
        ]);
        Menu::set('rbac.roles', [
            'label' => '角色列表',
            'url' => ['/admin/role/index'],
            'priority' => 101
        ]);
        Menu::set('rbac.permissions', [
            'label' => '权限列表',
            'url' => ['/admin/permission/index'],
            'priority' => 102
        ]);

        Console::output('注册用户模块后台管理菜单完成 ....');
    }
}
