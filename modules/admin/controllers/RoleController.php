<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\rbac\Item;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;
use app\modules\admin\components\Controller;
use app\modules\admin\models\AuthItemForm;

/**
 * RoleController implements the CRUD actions for User model.
 */
class RoleController extends Controller
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

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $authManager = Yii::$app->getAuthManager();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $authManager->getRoles(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('index', [
            'authItemForm' => new AuthItemForm(['type' => Item::TYPE_ROLE]),
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $authManager = Yii::$app->getAuthManager();
        $authItemForm = new AuthItemForm(['type' => Item::TYPE_ROLE]);

        $post = Yii::$app->getRequest()->post();
        if ($authItemForm->load($post) && $authItemForm->save()) {
            return $this->message('创建成功', 'success', 'index', 'flash');
        }

        return $this->render('create', [
            'authItemForm' => $authItemForm,
            'rolesDataProvider' => new ArrayDataProvider([
                'models' => $authManager->getRoles()
            ]),
            'permissionsDataProvider' => new ArrayDataProvider([
                'models' => $authManager->getPermissions()
            ]),
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $role = $this->findItem($id);

        $authManager = Yii::$app->getAuthManager();
        $authItemForm = new AuthItemForm(['type' => Item::TYPE_ROLE]);
        $authItemForm->setAttributes(ArrayHelper::toArray($role));
        $authItemForm->setItem($role);

        $post = Yii::$app->getRequest()->post();
        if ($authItemForm->load($post) && $authItemForm->save()) {
            return $this->message('修改成功', 'success', 'index', 'flash');
        }

        return $this->render('update', [
            'authItemForm' => $authItemForm,
            'children' => $authManager->getChildren($authItemForm->name),
            'rolesDataProvider' => new ArrayDataProvider([
                'models' => array_filter($authManager->getRoles(), function ($role) use ($authItemForm, $authManager) {
                    return ($role->name !== $authItemForm->name) && !$authManager->hasChild($role, $authItemForm); // 子角色中不能显示父角色和当前角色
                })
            ]),
            'permissionsDataProvider' => new ArrayDataProvider([
                'models' => $authManager->getPermissions()
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
        $role = $this->findItem($id);
        if (Yii::$app->getAuthManager()->remove($role)) {
            $this->message('删除成功!', 'success', null, 'flash');
        }
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findItem($id)
    {
        if (($role = Yii::$app->getAuthManager()->getRole($id)) !== null) {
            return $role;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
