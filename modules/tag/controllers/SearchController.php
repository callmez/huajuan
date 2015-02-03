<?php
namespace app\modules\tag\controllers;

use yii\filters\AccessControl;
use app\components\Controller;
use app\modules\tag\models\TagSearch;

class SearchController extends Controller
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

    public function actionIndex($name)
    {
        $searchModel = new TagSearch();
        $dataProvider = $searchModel->search([
            $searchModel->formName() => [
                'name' => $name
            ]
        ]);
        $data = array_map(function($model){
            return $model->getAttributes(['id', 'name', 'icon', 'description']);
        }, $dataProvider->getModels());
        return $this->message($data, 'success');
    }
}