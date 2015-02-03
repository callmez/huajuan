<?php
namespace app\modules\user\controllers;

use Yii;
use yii\filters\AccessControl;
use app\modules\user\models\Hate;
use app\modules\question\components\Controller;

class HateController extends Controller
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

        list($result, $like) = Hate::Question($user, $question);

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

        list($result, $like) = Hate::Answer($user, $answer);

        if ($result) {
            return $this->message('提交成功!', 'success');
        } else {
            return $this->message($like ? $like->getErrors() : '提交失败!');
        }
    }
}