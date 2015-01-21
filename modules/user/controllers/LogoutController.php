<?php
namespace app\modules\user\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;

class LogoutController extends Controller
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

    public function actionIndex()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}