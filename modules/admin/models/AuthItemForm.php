<?php
namespace app\modules\admin\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\rbac\Item;

/**
 * RBAC角色和权限
 * @package app\modules\user\models
 */
class AuthItemForm extends Model
{
    /**
     * @var integer the type of the item. This should be either [[TYPE_ROLE]] or [[TYPE_PERMISSION]].
     */
    public $type;
    /**
     * @var string the name of the item. This must be globally unique.
     */
    public $name;
    /**
     * @var string the item description
     */
    public $description;
    /**
     * @var string name of the rule associated with this item
     */
    public $ruleName;
    /**
     * @var mixed the additional data associated with this item
     */
    public $data;
    /**
     * @var integer UNIX timestamp representing the item creation time
     */
    public $createdAt;
    /**
     * @var integer UNIX timestamp representing the item updating time
     */
    public $updatedAt;

    /**
     * 子项, 拥有的权限或子角色
     * @var array
     */
    public $children = [];

    /**
     * 为空代表创建,否则则修改item属性
     * @var null
     */
    private $_item = null;

    public function init()
    {
        parent::init();
        if ($this->type === null) {
            throw new InvalidConfigException("The 'type' property must be set.");
        }
    }

    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            [['name'], 'match', 'pattern' => '/[A-Za-z][A-Za-z0-9_]+/i', 'message' => '关键字只能使用字母数字和-_符号且不能数字开头'],
            [['ruleName'], 'default', 'value' => null],
            [['data', 'children'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'type' => '类型',
            'name' => '关键字',
            'description' => $this->type == Item::TYPE_ROLE ? '角色名' : '权限名',
            'ruleName' => '规则名',
            'data' => '内容',
            'createdAt' => '创建时间',
            'updatedAt' => '修改时间'
        ];
    }

    public function setItem(Item $item)
    {
        return $this->_item = $item;
    }

    public function getItem()
    {
        return $this->_item;
    }

    protected function setItemAttributes()
    {
        Yii::configure($this->_item, [
            'name' => $this->name,
            'description' => $this->description,
            'ruleName' => $this->ruleName,
            'data' => $this->data
        ]);
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }
        $authManager = Yii::$app->getAuthManager();

        if ($this->_item !== null) { // 修改
            if ($this->type != $this->_item->type) { //类型必须一样
                return $this->addError('name', "{$this->_item->name}类型不正确.不能修改");
            }
            $name = $this->_item->name;
            $this->setItemAttributes();
            $result = $authManager->update($name, $this->_item);
        } else {// 创建
            $methods = [
                Item::TYPE_ROLE => ['getRole', 'createRole'],
                Item::TYPE_PERMISSION => ['getPermission', 'createPermission']
            ];
            list ($get, $create) = $methods[$this->type];
            if ($authManager->{$get}($this->name)) {
                return $this->addError('name', '关键字已存在');
            }
            $this->_item = $authManager->{$create}($this->name);
            $this->setItemAttributes();
            $result = $authManager->add($this->_item);
        }
        if ($result && !empty($this->children)) {
            $authManager->removeChildren($this->_item);
            foreach($this->children as $child) {
                $child = $authManager->getRole($child) ?: $authManager->getPermission($child);
                if (!$child) {
                    continue;
                }
                $authManager->addChild($this->_item, $child);
            }
        }
        return $result;
    }
}
