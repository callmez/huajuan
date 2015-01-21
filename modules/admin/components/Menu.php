<?php
namespace app\modules\admin\components;

use Yii;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;

class Menu
{
    const MENU_BASE_KEY = 'admin.menu';

    /**
     * 设置菜单(如果菜单已存在则覆盖)
     * ~~~
     * Menu::set('user.name', [
     *     'label' => '用户列表',
     *     'url' => ['/user/admin/user/index'],
     *     'icon' => 'fa-user',
     *     'priority' => 9
     * ]);
     * ~~~
     * @param $menuKey 可以多级设置.最多设置二级
     * @param array $options 菜单设置
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public static function set($menuKey, array $options)
    {
        $menuKeys = explode('.', $menuKey);
        if (count($menuKeys) > 2) {
            throw new InvalidConfigException("Can only support 2 levels of menus"); // 最多只能支持二级菜单
        }

        $menus = static::get();
        $_menu = & $menus;
        while (count($menuKeys) > 1) {
            $menuKey = array_shift($menuKeys);
            if (!isset($_menu[$menuKey]) || !is_array($_menu[$menuKey])) {
                $_menu[$menuKey] = [
                    'label' => $menuKey,
                    'priority' => 100
                ];
            }
            if (!isset($_menu[$menuKey]['items'])) {
                $_menu[$menuKey]['items'] = [];
            }
            $_menu = & $_menu[$menuKey]['items'];
        }

        $menuKey = array_shift($menuKeys);
        $_menu[$menuKey] = array_merge(isset($_menu[$menuKey]) ? $_menu[$menuKey] : [], array_merge([
            'label' => ArrayHelper::remove($options, 'label', $menuKey), // 菜单名称
            'url' => ArrayHelper::remove($options, 'url'), // 链接
            'priority' => ArrayHelper::remove($options, 'priority', 10), // 优先级
        ], $options));
        ArrayHelper::multisort($_menu, 'priority'); // 排序

        return Yii::$app->get('config')->set(static::MENU_BASE_KEY, $menus);
    }

    /**
     * 获取指定菜单
     * ~~~
     * Menu::get('user.list')
     * ~~~
     * @param string $menuKey
     * @return array
     */
    public static function get($menuKey = null)
    {
        $menuKey !== null && $menuKey = '.' . implode('.items.', explode('.', $menuKey));
        return (array)Yii::$app->get('config')->get(static::MENU_BASE_KEY . $menuKey, []);
    }

    /**
     * 删除菜单
     * @param $menuKey
     * @return mixed
     */
    public static function delete($menuKey)
    {
        return Yii::$app
            ->get('config')
            ->delete(static::MENU_BASE_KEY . '.' . implode('.items.', explode('.', $menuKey)));
    }

}