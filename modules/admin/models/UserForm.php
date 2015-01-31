<?php
namespace app\modules\admin\models;

use Yii;
use app\modules\user\models\User;

class UserForm extends User
{
    public $password;

    public function rules()
    {
        // 创建用户密码必填
        $requiredAttributes = $this->isNewRecord ? ['username', 'email', 'password'] : ['username', 'email'];
        return array_merge([
            [['username', 'email'], 'filter', 'filter' => 'trim'],
            [$requiredAttributes, 'required'],
            [['email'], 'email'],
            [['username'], 'string', 'min' => 2, 'max' => 255],

            [['username'], 'unique', 'targetClass' => 'app\modules\user\models\User', 'message' => '用户名已经被注册过.'],
            [['email'], 'unique', 'targetClass' => 'app\modules\user\models\User', 'message' => '邮箱已经被使用过.'],

            [['password'], 'string', 'min' => 4],
            [['password'], 'changePassword'],
        ], parent::rules());
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'password' => '密码'
        ]);
    }

    /**
     * 改密码
     * @param $attribute
     * @param $params
     */
    public function changePassword($attribute, $params)
    {
        if (!empty($this->password)) {
            $this->setPassword($this->password);
        }
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord) { // 创建用户 生成authKey
            $this->generateAuthKey();
        }
        return parent::beforeSave($insert);
    }

}