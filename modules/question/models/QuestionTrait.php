<?php
namespace app\modules\question\models;

use Yii;
use yii\db\ActiveRecord;
use app\modules\user\models\User;
use app\modules\user\models\Hate;
use app\modules\user\models\Like;

/**
 * Question 和 Answer 共用特性类
 * 注意: 派生类必须定义 TYPE 常量
 * @package app\modules\question\models
 */
trait QuestionTrait
{
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

    /**
     * 获取关联作者
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }

    /**
     * 获取指定用户的踩记录
     * @param null|int $authorId
     * @return mixed
     */
    public function getHate($authorId = null)
    {
        return $this->hasOne(Hate::className(), [
            'target_id' => 'id',
        ])->andWhere([
            'target_type' => self::TYPE,
            'author_id' => $authorId ?: Yii::$app->getUser()->getId()
        ]);
    }

    /**
     * 获取指定用户的赞记录
     * @param null|int $authorId
     * @return mixed
     */
    public function getLike($authorId = null)
    {
        return $this->hasOne(Like::className(), [
            'target_id' => 'id',
        ])->andWhere([
            'target_type' => self::TYPE,
            'author_id' => $authorId ?: Yii::$app->getUser()->getId()
        ]);
    }
}