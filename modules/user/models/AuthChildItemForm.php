<?php
namespace app\modules\user\models;

use Yii;
use yii\base\Model;
use yii\rbac\Item;

class AuthChildItemForm extends Model
{
    public $parent;
    public $child;
    /**
     * 是否为添加 子分类, 否则删除子分类
     * @var bool
     */
    public $add = true;

    public function rules()
    {
        return [
            [['parent', 'child'], 'required'],
            [['parent', 'child'], 'checkItem', 'skipOnEmpty' => false],
            [['add'], 'safe']
        ];
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function checkItem($attribute, $params)
    {
        if (!($this->$attribute instanceof Item)) {
            $this->addError($attribute, "{$attribute}必须为\yii\rbac\Item实例");
        }
    }

    public function saveChildItem()
    {
        if (!$this->validate()) {
            return false;
        }
        $authManager = Yii::$app->getAuthManager();
        if ($this->add) { // 添加
            if ($authManager->hasChild($this->parent, $this->child)) {
                return true; // 已存在的直接返回成功
                $this->addError('child', "{$this->child->name}已经是{$this->parent->name}的子" . ($this->child->type == Item::TYPE_ROLE ? '角色' : '权限'));
                return false;
            }
            return $authManager->addChild($this->parent, $this->child);
        } else { // 删除
            return $authManager->removeChild($this->parent, $this->child);
        }


    }
}
