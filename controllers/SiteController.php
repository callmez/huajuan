<?php

namespace app\controllers;

use app\components\ControllerTrait;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\modules\admin\components\Menu;

class SiteController extends Controller
{
    use ControllerTrait;
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionTest()
    {
        echo count(Yii::$app->config['test']);
        Menu::delete('user');exit;
        Menu::set('user.list', [
            'label' => '用户列表',
            'url' => ['/user/admin/user/index'],
            'priority' => 10
        ]);
        Menu::set('user', [
            'label' => '用户管理',
            'url' => ['/user/admin/user/index'],
            'icon' => 'fa-user',
            'priority' => 10
        ]);
        Menu::set('user.name', [
            'label' => '用户名称',
            'url' => ['/user/admin/user/index'],
            'priority' => 9
        ]);
        Menu::set('test', [
            'label' => '测试',
            'url' => ['/user/admin/user/index'],
            'priority' => 10
        ]);
    }
}
