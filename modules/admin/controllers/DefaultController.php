<?php

namespace app\modules\admin\controllers;

use yii\web\Controller;
use app\modules\admin\components\AdminController;

class DefaultController extends AdminController
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
