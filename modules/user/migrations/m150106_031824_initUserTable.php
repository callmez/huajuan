<?php

use yii\db\Schema;
use yii\db\Migration;
use yii\helpers\Console;
use app\modules\user\models\User;
use app\components\MigrationTrait;
use app\modules\user\models\SignupForm;

/**
 * 初始化用户表数据
 */
class m150106_031824_initUserTable extends Migration
{
    use MigrationTrait;
    public function up()
    {
        $tableName = User::tableName();
        $this->createTable($tableName, [
            'id' => Schema::TYPE_PK,
            'username' => Schema::TYPE_STRING . ' NOT NULL',
            'email' => Schema::TYPE_STRING . ' NOT NULL',
            'auth_key' => Schema::TYPE_STRING . '(32) NOT NULL',
            'password_hash' => Schema::TYPE_STRING . ' NOT NULL',
            'password_reset_token' => Schema::TYPE_STRING,

            'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 10',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $this->tableOptions);

        $this->createIndex('username', $tableName, 'username', true);
        $this->createIndex('email', $tableName, 'email', true);

        $this->createFounder();
    }

    public function down()
    {
        $this->dropTable(User::tableName());
    }

    /**
     * 创建创始人数据
     */
    public function createFounder()
    {
        Console::output("\n请先创建创始人账户:   ");

        $user = $this->saveFounderData(new SignupForm());

        $uid = $user ? $user->id : 1; // 用户创建成功则指定用户id,否则指定id为1的用户为创始人.
        $founder = Yii::$app->authManager->getRole('founder');
        $founder && Yii::$app->authManager->assign($founder, $uid); // 指定创始人身份
        Console::output("创始人创建" . ($user ? '成功' : "失败, 将以ID为1的用户设置为创始人,请手动创建创始人用户\n"));
    }

    /**
     * 用户创建交互
     * @param $_model
     * @return mixed
     */
    private function saveFounderData($_model)
    {
        $model = clone $_model;
        $model->username = Console::prompt('请输入创始人用户名', ['default' => 'admin']);
        $model->email = Console::prompt('请输入创始人邮箱', ['default' => 'admin@admin.com']);
        $model->password = Console::prompt('请输入创始人密码', ['default' => 'admin']);

        if (!($user = $model->signup())) {
            Console::output(Console::ansiFormat("\n输入数据验证错误:", [Console::FG_RED]));
            foreach ($model->getErrors() as $k => $v) {
                Console::output(Console::ansiFormat(implode("\n", $v), [Console::FG_RED]));
            }
            if (Console::confirm("\n是否重新创建创始人账户:")) {
                $user = $this->saveFounderData($_model);
            }
        }
        return $user;
    }
}
