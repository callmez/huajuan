<?php
namespace app\models;

use yii\db\ActiveRecord;

class Post extends ActiveRecord
{
    /**
     * 审核通过
     */
    const STATUS_ACTIVE = 1;
    /**
     * 审核
     */
    const STATUS_AUDIT = 0;
    /**
     * 已删除
     */
    const STATUS_DELETED = -1;

    public static function tableName()
    {
        return '{{%post}}';
    }

    public function rules()
    {
        return [
            [['subject'], 'required'],
            [['name'], 'unique'],
            [['description'], 'default', 'value' => ''],
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
            ],
        ];
    }

    public function getItems()
    {
        return $this->hasMany(TagItem::className(), ['id' => 'tid']);
    }
}