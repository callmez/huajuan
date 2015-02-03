<?php
namespace app\modules\question\components;

use yii\web\NotFoundHttpException;
use app\modules\question\models\Answer;
use app\modules\question\models\Question;

class Controller extends \app\components\Controller
{
    /**
     * 通过ID获取指定问题
     */
    protected function findQuestion($id, \Closure $callback = null)
    {
        $query = Question::find();
        $callback !== null && $callback($query);
        if (($model = $query->andWhere(['id' => $id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    /**
     * 通过ID获取指定问题
     */
    protected function findAnswer($id, \Closure $callback = null)
    {
        $query = Answer::find();
        $callback !== null && $callback($query);
        if (($model = $query->andWhere(['id' => $id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}