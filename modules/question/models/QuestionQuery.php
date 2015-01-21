<?php
namespace app\modules\question\models;

use yii\db\ActiveQuery;

class QuestionQuery extends ActiveQuery
{
    public function active()
    {
        $this->andWhere(['status' => Question::STATUS_ACTIVE]);
        return $this;
    }
}