<?php
namespace tests\codeception\unit\modules\admin\components;

use Yii;
use yii\codeception\TestCase;
use Codeception\Specify;
use app\modules\admin\components\Menu;

class MenuTest extends TestCase
{
    use Specify;

    public function testMenu()
    {
        Menu::delete('user', null);
        Menu::set('user.list', [
            'label' => '用户列表',
            'url' => ['/user/admin/user/list'],
            'priority' => 10
        ]);
        $this->assertEquals([
            'label' => '用户列表',
            'url' => ['/user/admin/user/list'],
            'priority' => 10
        ], Menu::get('user.list'));

        Menu::set('user', [
            'label' => '用户管理',
            'url' => ['/user/admin/user/index'],
            'priority' => 10
        ]);

        $this->assertEquals([
            'label' => '用户管理',
            'url' => ['/user/admin/user/index'],
            'priority' => 10,
            'items' => [
                'list' => [
                    'label' => '用户列表',
                    'url' => ['/user/admin/user/list'],
                    'priority' => 10
                ]
            ]
        ], Menu::get('user'));

//        Menu::set('user.name', [
//            'label' => '用户名称',
//            'url' => ['/user/admin/user/index'],
//            'priority' => 9
//        ]);
//        Menu::set('test', [
//            'label' => '测试',
//            'url' => ['/user/admin/user/index'],
//            'priority' => 10
//        ]);
    }
}