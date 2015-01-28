<?php

use yii\helpers\Console;
use yii\rbac\DbManager;
use app\components\MigrationTrait;

require Yii::getAlias('@yii/rbac/migrations/m140506_102106_rbac_init.php');

/**
 * 初始化RBAC表(如果RBAC设置为数据库记录方式)
 */
class m150106_020316_initRbacTable extends m140506_102106_rbac_init
{
    use MigrationTrait;
    public function up()
    {
        if ($this->isDbManager()) {
            parent::up();
        }

        $this->initRbac();
    }

    public function down()
    {
        if ($this->isDbManager()) {
            parent::down();
        }
    }

    /**
     * 是否数据库类型Rbac
     * @return bool
     */
    public function isDbManager()
    {
        return Yii::$app->authManager instanceof DbManager;
    }

    /**
     * 初始默认角色数据
     */
    public function initRbac()
    {
        Console::output('初始化RBAC数据 ....');
        $auth = Yii::$app->authManager;

        /* ================= 权限 ================= */
        $visitAdmin = $auth->createPermission('visitAdmin');
        $visitAdmin->description = '访问后台管理界面权限';
        $auth->add($visitAdmin);

        /* ================= 身份 ================= */
        $guest = $auth->createRole('guest'); // 匿名用户
        $guest->description = '匿名用户';
        $auth->add($guest);

        $user = $auth->createRole('user'); //普通用户
        $user->description = '普通用户';
        $auth->add($user, $guest); //普通用户 > 匿名用户

        $admin = $auth->createRole('admin'); // 管理员
        $admin->description = '管理员';
        $auth->add($admin);
        $auth->addChild($admin, $user); // 管理员 > 普通用户
        $auth->addChild($admin, $visitAdmin); // 管理员可以访问后台

        $founder = $auth->createRole('founder'); // 创始人
        $founder->description = '创始人';
        $auth->add($founder);
        $auth->addChild($founder, $admin); // 创始人 > 管理员

        Console::output('初始化RBAC数据完成 ....');
    }
}
