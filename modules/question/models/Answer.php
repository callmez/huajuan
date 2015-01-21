<?php
namespace app\modules\question\models;

use Yii;
use app\models\Post;

/**
 * 问题回答
 * @package app\modules\question\models
 */
class Answer extends Post
{
    /**
     * 公用QuestionTrait类
     */
    use QuestionTrait;
    const TYPE = 'answer';
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

    /**
     * type为answer的为问题
     */
    public static function find()
    {
        return (new AnswerQuery(get_called_class()))->where([
            'type' => self::TYPE,
            'status' => self::STATUS_ACTIVE
        ]);
    }

    public function rules()
    {
        return [
            [['pid', 'content', 'author_id'], 'required']
        ];
    }

    /**
     * 获取问题
     */
    public function getQuestion()
    {
        return $this->hasOne(Question::className(), ['id' => 'pid']);
    }

    /**
     * 审核
     * @return bool
     */
    public function setActive()
    {
        $return = true;
        if ($this->status != static::STATUS_ACTIVE ) {
            $this->status = static::STATUS_ACTIVE;
            if (!$this->isNewRecord && ($return = $this->updateAttributes(['status' => static::STATUS_ACTIVE]))) {
                $return = $this->question->updateCounters([ //更新统计
                    'comment_count' => 1
                ]);
            }
        }
        return $return;
    }
}