<?php
namespace app\modules\admin\widgets;

use yii\base\Widget;
use yii\widgets\Menu;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * 后台侧边栏菜单widget
 * @package app\modules\admin\widgets
 */
class SidebarMenu extends Menu
{
    /**
     * @inherit
     * @var string
     */
    public $linkTemplate = '<a href="{url}"><i class="fa {icon}"></i> <span>{label}</span> {badge}</a>';
    /**
     * @inherit
     * @var string
     */
    public $labelTemplate = '<a href="javascript:;"> <i class="fa {icon}"></i> <span>{label}</span> {badge}</a>';
    /**
     * @inherit
     * @var string
     */
    public $submenuTemplate = "\n<ul class=\"treeview-menu\">\n{items}\n</ul>\n";
    /**
     * @inherit
     * @var string
     */
    public $activateParents = true;
    /**
     * @inherit
     * @var array
     */
    public $options = [
        'class' => 'sidebar-menu'
    ];
    /**
     * 默认的Icon class
     * @var string
     */
    public $defaultIconClass = 'fa-list';
    /**
     * 默认的子菜单icon class
     * @var string
     */
    public $defaultSubmenuIconClass = 'fa fa-angle-double-right';
    /**
     * 默认的徽章图标, 默认为有子菜单的内容, 无子菜单则空
     * @var string
     */
    public $defaultBadge = '<i class="fa pull-right fa-angle-left"></i>';
    /**
     * 有子菜单是的items样式, 为菜单下拉子菜单效果
     * @var string
     */
    public $hasItemsClass = 'treeview';
    /**
     * 当前流程是否处理子菜单流程
     * @var bool
     */
    public $isSubmenu = false;

    /**
     * @inherit
     * @param array $items
     * @return string
     */
    protected function renderItems($items)
    {
        $n = count($items);
        $lines = [];
        foreach ($items as $i => $item) {
            $options = array_merge($this->itemOptions, ArrayHelper::getValue($item, 'options', []));
            $tag = ArrayHelper::remove($options, 'tag', 'li');
            $class = [];
            if ($item['active']) {
                $class[] = $this->activeCssClass;
            }
            if ($i === 0 && $this->firstItemCssClass !== null) {
                $class[] = $this->firstItemCssClass;
            }
            if ($i === $n - 1 && $this->lastItemCssClass !== null) {
                $class[] = $this->lastItemCssClass;
            }
            if (!empty($class)) {
                if (empty($options['class'])) {
                    $options['class'] = implode(' ', $class);
                } else {
                    $options['class'] .= ' ' . implode(' ', $class);
                }
            }

            $menu = $this->renderItem($item);
            if (!empty($item['items'])) {
                $this->isSubmenu = true;
                $menu .= strtr($this->submenuTemplate, [
                    '{items}' => $this->renderItems($item['items']),
                ]);
                $this->isSubmenu = false;
                $options['class'] = (isset($options['class']) ? $options['class'] : '') . ' ' . $this->hasItemsClass;
            }
            $lines[] = Html::tag($tag, $menu, $options);
        }

        return implode("\n", $lines);
    }

    /**
     * @inherit
     * @param array $item
     * @return string
     */
    protected function renderItem($item)
    {
        $hasItems = !empty($item['items']);
        if (!array_key_exists('icon', $item)) {
            $item['icon'] = $this->isSubmenu ? $this->defaultSubmenuIconClass : $this->defaultIconClass;
        }
        if (!array_key_exists('badge', $item)) {
            $item['badge'] = $hasItems ? $this->defaultBadge : null;
        }
        if (!$hasItems && isset($item['url']) ) {
            $template = ArrayHelper::getValue($item, 'template', $this->linkTemplate);

            return strtr($template, [
                '{url}' => Html::encode(Url::to($item['url'])),
                '{icon}' => $item['icon'],
                '{label}' => $item['label'],
                '{badge}' => $item['badge']
            ]);
        } else {
            $template = ArrayHelper::getValue($item, 'template', $this->labelTemplate);

            return strtr($template, [
                '{label}' => $item['label'],
                '{icon}' => $item['icon'],
                '{badge}' => $item['badge']
            ]);
        }
    }
}