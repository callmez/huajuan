<?php

use yii\db\Schema;
use yii\db\Migration;
use yii\helpers\Console;
use app\modules\admin\components\Menu;

/**
 * 初始化用户模块所需数据
 */
class m150001_145055_initUser extends Migration
{
    public function up()
    {
        $this->initMenu();
    }

    public function down()
    {
    }

    /**
     * 初始化后台菜单
     */
    public function initMenu()
    {
        Console::output('注册用户模块后台管理菜单 ....');

        //用户
        Menu::set('user', [
            'label' => '用户管理',
            'url' => ['/user/admin/user/index'],
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
            'url' => ['/user/admin/role/index'],
            'priority' => 101
        ]);
        Menu::set('rbac.permissions', [
            'label' => '权限列表',
            'url' => ['/user/admin/permission/index'],
            'priority' => 102
        ]);

        Console::output('注册用户模块后台管理菜单完成 ....');
    }
}
