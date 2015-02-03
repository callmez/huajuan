<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\rbac\Item;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;
use app\modules\admin\components\Controller;
use app\modules\admin\models\AuthItemForm;

/**
 * PermissionController implements the CRUD actions for User model.
 */
class PermissionController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $authManager = Yii::$app->getAuthManager();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $authManager->getPermissions(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('index', [
            'authItemForm' => new AuthItemForm(['type' => Item::TYPE_PERMISSION]),
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionCreate()
    {
        $authManager = Yii::$app->getAuthManager();
        $authItemForm = new AuthItemForm(['type' => Item::TYPE_PERMISSION]);
        $post = Yii::$app->getRequest()->post();
        if ($authItemForm->load($post) && $authItemForm->save()) {
            return $this->message('创建成功', 'success', 'index', 'flash');
        }

        return $this->render('create', [
            'authItemForm' => $authItemForm,
            'permissionsDataProvider' => new ArrayDataProvider([
                'models' => $authManager->getPermissions()
            ]),
        ]);
    }

    public function actionUpdate($id)
    {
        $permission = $this->findItem($id);

        $authManager = Yii::$app->getAuthManager();
        $authItemForm = new AuthItemForm(['type' => Item::TYPE_PERMISSION]);
        $authItemForm->setAttributes(ArrayHelper::toArray($permission));
        $authItemForm->setItem($permission);

        $post = Yii::$app->getRequest()->post();
        if ($authItemForm->load($post) && $authItemForm->save()) {
            return $this->message('修改成功', 'success', 'index', 'flash');
        }

        return $this->render('update', [
            'authItemForm' => $authItemForm,
            'children' => $authManager->getChildren($authItemForm->name),
            'permissionsDataProvider' => new ArrayDataProvider([
                'models' => array_filter($authManager->getPermissions(), function ($permission) use ($authItemForm) { // 过滤自己角色
                    return ($permission->name !== $authItemForm->name);
                })
            ])
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $permission = $this->findItem($id);
        if (Yii::$app->getAuthManager()->remove($permission)) {
            return $this->message('删除成功!', 'success', 'index', 'flash');
        }
    }

    protected function findItem($id)
    {
        if (($permission = Yii::$app->getAuthManager()->getPermission($id)) !== null) {
            return $permission;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
