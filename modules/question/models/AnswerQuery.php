<?php
namespace app\modules\question\models;

use yii\db\ActiveQuery;

class AnswerQuery extends ActiveQuery
{
    public function active()
    {
        $this->andWhere(['status' => Answer::STATUS_ACTIVE]);
        return $this;
    }
}