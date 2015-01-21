<?php
namespace app\modules\user\models;

use yii\db\ActiveRecord;

/**
 * 主题元数据表,派生类必须定义TYPE常量来标记派生的元数据类型
 * @package app\models
 */
class Meta extends ActiveRecord
{
    const STATUS_ACTIVE = 0;
    const STATUS_DELETED = -1;

    public static function tableName()
    {
        return '{{%user_meta}}';
    }

    /**
     * @inherit
     */
    public static function find()
    {
        return parent::find()->where([
            'status' => self::STATUS_ACTIVE,
            'type' => static::TYPE
        ]);
    }

    /**
     * 默认规则
     */
    public function rules()
    {
        return [
            [['target_id', 'target_type', 'author_id'], 'required'],
            [['target_type'], 'string', 'max' => 50],
            [['target_id', 'author_id'], 'integer'],
            [['value'], 'default', 'value' => 1],
            [['status'], 'default', 'value' => self::STATUS_ACTIVE]
        ];
    }

    /**
     * 自动更新created_at和updated_at时间
     * @return array
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
            ],
            'type' => [
                'class' => 'yii\behaviors\AttributeBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'type'
                ],
                'value' => function ($event) {
                    $sender = $event->sender;
                    return $sender::TYPE;
                },
            ]
        ];
    }
}