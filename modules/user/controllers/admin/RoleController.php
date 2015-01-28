<?php

namespace app\modules\user\controllers\admin;

use Yii;
use yii\helpers\ArrayHelper;
use yii\rbac\Item;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;
use app\modules\admin\components\Controller;
use app\modules\user\models\AuthItemForm;
use app\modules\user\models\AuthChildItemForm;

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
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
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
        $authChildItemForm = new AuthChildItemForm;

        return $this->render('create', [
            'authItemForm' => $authItemForm,
            'authChildItemForm' => $authChildItemForm,
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
    public function actionUpdate($name)
    {
        $authManager = Yii::$app->getAuthManager();
        $role = $authManager->getRole($name);
        if ($role === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $authItemForm = new AuthItemForm(ArrayHelper::toArray($role));
        $authChildItemForm = new AuthChildItemForm;

        $children = $authManager->getChildren($authItemForm->name);

        return $this->render('update', [
            'authItemForm' => $authItemForm,
            'authChildItemForm' => $authChildItemForm,
            'rolesDataProvider' => new ArrayDataProvider([
                'models' => array_filter($authManager->getRoles(), function ($role) use ($authItemForm, $authManager) {
                    return ($role->name !== $authItemForm->name) && !$authManager->hasChild($role, $authItemForm); // 子角色中不能显示父角色和当前角色
                })
            ]),
            'permissionsDataProvider' => new ArrayDataProvider([
                'models' => $authManager->getPermissions()
            ]),
            'childRoles' => array_diff_key($children, $authManager->getPermissionsByRole($authItemForm->name)), // 获取非权限的的角色
            'childPermissions' => $authManager->getPermissionsByRole($authItemForm->name, false)
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
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
