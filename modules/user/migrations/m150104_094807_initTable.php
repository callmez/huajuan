<?php

use yii\db\Schema;
use app\components\Migration;
use app\modules\user\models\User;

class m150104_094807_initTable extends Migration
{
    public function up()
    {
        $this->createUserTable();
    }

    public function down()
    {
        $this->dropTable(User::tableName());
    }

    public function createUserTable()
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
        $this->createIndex('user_confirmation', $tableName, 'id, confirmation_token', true);
        $this->createIndex('user_recovery', $tableName, 'id, recovery_token', true);

    }
}
