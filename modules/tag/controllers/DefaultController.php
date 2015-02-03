<?php

namespace app\modules\tag\controllers;

use Yii;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use app\components\Controller;
use app\modules\tag\models\Tag;

class DefaultController extends Controller
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
        return $this->render('index');
    }

    public function actionCreate()
    {
        $model = new Tag();
        if ($model->load(Yii::$app->request->post())) {
            if (isset($_POST['ajax'])) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            $model->author_id = Yii::$app->user->getId();
            if ($model->save() && $model->setActive()) { // TODO 改为开关审核
                return $this->message($model->getAttributes(), 'success');
            } else {
                return $this->message($model->getErrors());
            }
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
