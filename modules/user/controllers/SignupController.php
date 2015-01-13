<?php

namespace app\modules\user\controllers;

use Yii;
use app\modules\user\models\SignupForm;

class SignupController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('index', [
            'model' => $model,
        ]);
    }

}
