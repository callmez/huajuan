<?php
namespace app\modules\user\controllers\admin;

use Yii;
use yii\rbac\Item;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider;
use app\modules\admin\models\AuthItemForm;
use app\modules\admin\components\Controller;
use app\modules\admin\models\AuthChildItemForm;

class RbacController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 角色列表
     * @return string
     */
    public function actionRoles()
    {
        $authManager = Yii::$app->getAuthManager();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $authManager->getRoles(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('roles', [
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * 添加角色
     * @return mixed
     */
    public function actionAddRole()
    {
        return $this->add(Item::TYPE_ROLE);
    }

    /**
     * 修改角色
     * @param $name string
     * @return string
     */
    public function actionUpdateRole($name)
    {
        $item = Yii::$app->getAuthManager()->getRole($name);
        return $this->update($item);
    }

    public function actionPermissions()
    {
        $authManager = Yii::$app->getAuthManager();
        $permissionsProvider = new ArrayDataProvider([
            'allModels' => $authManager->getPermissions(Yii::$app->getUser()->getId()),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('permissions', [
            'permissionsProvider' => $permissionsProvider,
            'authItemForm' => new AuthItemForm(['type' => Item::TYPE_PERMISSION])
        ]);
    }

    /**
     * 添加权限
     * @return mixed
     */
    public function actionAddPermission()
    {
        return $this->add(Item::TYPE_PERMISSION);
    }

    /**
     * 修改权限
     * @param $name string
     * @return string
     */
    public function actionUpdatePermission($name)
    {
        $item = Yii::$app->getAuthManager()->getPermission($name);
        return $this->update($item);
    }

    /**
     * 添加角色权限
     * @param $type
     * @return string
     */
    public function add($type)
    {
        $authManager = Yii::$app->getAuthManager();

        $authItemForm = new AuthItemForm(['type' => $type]);
        $authChildItemForm = new AuthChildItemForm;

        /**
         * 添加item和子item
         */
        if ($authItemForm->load($_POST) && $authItemForm->saveItem()) {
            $methodGet = $type == Item::TYPE_ROLE ? 'getRole' : 'getPermission';

            $item = $authManager->$methodGet($authItemForm->name);

            $authChildItemFormName = $authChildItemForm->formName();
            //必须先创建item才能创建子item
            if (isset($_POST[$authChildItemFormName]['child'])) {

                //子item提交
                foreach ((array)$_POST[$authChildItemFormName]['child'] as $_type => $child) {
                    $methodGet = $_type == Item::TYPE_ROLE ? 'getRole' : 'getPermission';
                    !is_array($child) && $child = [];
                    foreach ($child as $k => $childName) {
                        $newChildNames[] = $childName;
                        // 已有的子item不做添加操作
                        if (isset($children[$childName])) {
                            continue;
                        }
                        $childItem = $authManager->$methodGet($childName);
                        $this->authChildItemFormSave($childItem, true, $item, $authChildItemForm);
                    }
                }
            }
            $this->flash('添加成功', 'success');
            
            $authItemForm = new AuthItemForm(['type' => $type]);// 新建一个model
        }

        if ($type == Item::TYPE_ROLE) { // 角色
            $params = [
                'rolesDataProvider' => new ArrayDataProvider([
                        'models' => $authManager->getRoles()
                    ]),
                'permissionsDataProvider' => new ArrayDataProvider([
                        'models' => $authManager->getPermissions()
                    ]),
                'childRoles' => [],
                'childPermissions' => []
            ];
        } else { //权限
            $params = [
                'permissionsDataProvider' => new ArrayDataProvider([
                        'models' => $authManager->getPermissions()
                    ]),
                'childPermissions' => []
            ];
        }

        return $this->render('update', array_merge([
            'type' => $type,
            'opeartion' => '添加',
            'authItemForm' => $authItemForm,
            'authChildItemForm' => $authChildItemForm,
        ], $params));
    }

    /**
     * 修改权限角色
     * @param $item
     * @return string|\yii\web\Response
     */
    protected function update($item)
    {
        if (empty($item)) {
            $this->message('抱歉, 找不到指定的角色或权限', 'error');
        }
        $authManager = Yii::$app->getAuthManager();

        //子Item
        $children = $authManager->getChildren($item->name);

        $authChildItemForm = new AuthChildItemForm;
        $authChildItemFormName = $authChildItemForm->formName();
        // 修改删除子角色或权限
        if (isset($_POST[$authChildItemFormName]['child'])) { // 由于childItem有外键约束,必须在item修改前修改

            $newChildNames = [];
            //子item提交修改
            foreach ((array)$_POST[$authChildItemFormName]['child'] as $_type => $child) {
                $methodGet = $_type == Item::TYPE_ROLE ? 'getRole' : 'getPermission';
                !is_array($child) && $child = [];
                foreach ($child as $k => $childName) {
                    $newChildNames[] = $childName;
                    // 已有的子item不做添加操作
                    if (isset($children[$childName])) {
                        continue;
                    }
                    $childItem = $authManager->$methodGet($childName);
                    $this->authChildItemFormSave($childItem, true, $item, $authChildItemForm);
                }
            }
            if ($newChildNames !== []) {
                //子item 删除
                foreach (array_diff(array_keys($children), $newChildNames) as $k => $childName) { //批量删除子item
                    $methodGet = $children[$childName]->type == Item::TYPE_ROLE ? 'getRole' : 'getPermission';
                    $childItem = $authManager->$methodGet($childName);
                    $this->authChildItemFormSave($childItem, false, $item, $authChildItemForm);
                }
            }
        }

        $authItemForm = new AuthItemForm(['type' => $item->type]);
        $authItemForm->setAttributes(ArrayHelper::toArray($item));

        //item 提交修改
        if ($authItemForm->load($_POST) && $authItemForm->saveItem($item)) {
            return $this->flash('更新成功', 'success', ['', 'name' => $item->name]);
        }

        if ($item->type == Item::TYPE_ROLE) { // 角色
            $params = [
                'rolesDataProvider' => new ArrayDataProvider([
                        'models' => array_filter($authManager->getRoles(), function ($role) use ($item, $authManager) {
                            return ($role->name !== $item->name) && !$authManager->hasChild($role, $item); // 过滤父角色和自己的角色
                        })
                    ]),
                'permissionsDataProvider' => new ArrayDataProvider([
                        'models' => $authManager->getPermissions()
                    ]),
                'childRoles' => array_diff_key($children, $authManager->getPermissionsByRole($item->name)), // 非权限必是角色
                'childPermissions' => $authManager->getPermissionsByRole($item->name, false) // 非递归的权限
            ];
        } else { //权限
            $params = [
                'permissionsDataProvider' => new ArrayDataProvider([
                        'models' => array_filter($authManager->getPermissions(), function ($permission) use ($item) {
                            return $item->name != $permission->name; //过滤掉自己的权限
                        })
                    ]),
                'childPermissions' => $children,
            ];
        }

        return $this->render('update', array_merge([
            'item' => $item,
            'opeartion' => '修改',
            'type' => $item->type,
            'authItemForm' => $authItemForm,
            'authChildItemForm' => $authChildItemForm,
        ], $params));
    }

    /**
     * 子Item Model 操作函数
     * @param $child 子item
     * @param $add 是否添加,否则删除
     * @return bool
     */
    protected function authChildItemFormSave($child, $add, $item, $authChildItemForm)
    {
        $_authChildItemForm = clone $authChildItemForm;
        $_authChildItemForm->setAttributes([
            'parent' => $item,
            'child' => $child,
            'add' => $add,
        ]);
        return $_authChildItemForm->saveChildItem();
    }

}
