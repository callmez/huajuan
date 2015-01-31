<?php

namespace app\modules\user\models;

use Yii;
use app\models\Post;
use app\modules\question\models\Answer;
use app\modules\question\models\Question;

/**
 * 用户赞记录
 * @package app\modules\forum\models
 */
class Like extends Meta
{
    const TYPE = 'like';

    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['author_id' => 'id']);
    }

    /**
     * 喜欢数据切换
     * @param User $user
     * @param ActiveRecord $model
     * @return array
     */
    protected static function toggleType(User $user, Post $model)
    {
        $data = [
            'target_id' => $model->id,
            'target_type' => $model::TYPE,
            'author_id' => $user->id,
            'status' => static::STATUS_ACTIVE
        ];
        if (!static::deleteAll($data + ['type' => static::TYPE])) { // 删除数据有行数则代表有数据,无行数则添加数据
            $like = new static();
            $like->setAttributes($data);
            $result = $like->save();
            if ($result) { // 如果是新增数据, 删除掉Hate的同类型数据
                $attributes = [
                    'like_count' => 1
                ];
                if (Hate::deleteAll($data + ['type' => Hate::TYPE])) { // 如果有删除hate数据, hate_count也要-1
                    $attributes['hate_count'] = -1;
                }
                $model->updateCounters($attributes);
            }
            return [$result, $like];
        }
        $model->updateCounters([
            'like_count' => -1
        ]);
        return [true, null];
    }

    /**
     * 赞问题(如果已经赞,则取消赞)
     * @param User $user
     * @param Question $question
     */
    public static function question(User $user, Question $question)
    {
        return static::toggleType($user, $question);
    }

    /**
     * 赞回答(如果已经赞,则取消赞)
     * @param User $user
     * @param Question $question
     */
    public static function answer(User $user, Answer $answer)
    {
        return static::toggleType($user, $answer);
    }
}
