<?php
namespace app\modules\user\controllers;

use Yii;
use yii\filters\AccessControl;
use app\modules\user\models\Like;
use app\modules\question\components\Controller;

class LikeController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ]
            ]
        ];
    }

    public function actionQuestion($id)
    {
        $question = $this->findQuestion($id);
        $user = Yii::$app->user->getIdentity();

        list($result, $like) = Like::Question($user, $question);

        if ($result) {
            return $this->message('提交成功!', 'success');
        } else {
            return $this->message($like ? $like->getErrors() : '提交失败!');
        }
    }

    public function actionAnswer($id)
    {
        $answer = $this->findAnswer($id);
        $user = Yii::$app->user->getIdentity();

        list($result, $like) = Like::Answer($user, $answer);

        if ($result) {
            return $this->message('提交成功!', 'success');
        } else {
            return $this->message($like ? $like->getErrors() : '提交失败!');
        }
    }
}