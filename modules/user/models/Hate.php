<?php

namespace app\modules\user\models;

use Yii;
use app\models\Post;
use app\modules\question\models\Answer;
use app\modules\question\models\Question;

/**
 * 用户踩记录
 * @package app\modules\forum\models
 */
class Hate extends Meta
{
    const TYPE = 'hate';

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
            $hate = new static();
            $hate->setAttributes($data);
            $result = $hate->save();
            if ($result) { // 如果是新增数据, 删除掉Like的同类型数据
                $attributes = [
                    'hate_count' => 1
                ];
                if (Like::deleteAll($data + ['type' => Like::TYPE])) { // 如果又删除数据, like_count也要-1
                    $attributes['like_count'] = -1;
                }
                $model->updateCounters($attributes);
            }
            return [$result, $hate];
        }
        $model->updateCounters([
            'hate_count' => -1
        ]);
        return [true, null];
    }

    /**
     * 踩问题(如果已经赞,则取消赞)
     * @param User $user
     * @param Question $question
     * @return array
     */
    public static function question(User $user, Question $question)
    {
        return static::toggleType($user, $question);
    }

    /**
     * 踩回答(如果已经踩,则取消踩)
     * @param User $user
     * @param Answer $answer
     * @return array
     */
    public static function answer(User $user, Answer $answer)
    {
        return static::toggleType($user, $answer);
    }
}
