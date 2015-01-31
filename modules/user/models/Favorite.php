<?php

namespace app\modules\user\models;

use Yii;
use app\modules\question\models\Question;

/**
 * 用户收藏
 * @package app\modules\forum\models
 */
class Favorite extends Meta
{
    const TYPE = 'favorite';

    /**
     * 收藏问题
     * @param User $user
     * @param Question $question
     */
    public static function question(User $user, Question $model)
    {
        $data = [
            'target_id' => $model->id,
            'target_type' => $model::TYPE,
            'author_id' => $user->id,
            'status' => static::STATUS_ACTIVE
        ];
        if (!static::deleteAll($data + ['type' => static::TYPE])) { // 删除数据有行数则代表有数据,无行数则添加数据
            $favorite = new static();
            $favorite->setAttributes($data);
            $result = $favorite->save();
            if ($result) {
                $model->updateCounters([
                    'favorite_count' => 1
                ]);
            }
            return [$result, $favorite];
        }
        $model->updateCounters([
            'favorite_count' => -1
        ]);
        return [true, null];
    }
}
