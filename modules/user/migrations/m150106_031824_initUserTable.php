<?php

use yii\db\Schema;
use yii\base\components\Migration;
use app\modules\user\models\User;

class m150106_031824_initUserTable extends Migration
{
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
    }

    public function down()
    {
        $this->dropTable(User::tableName());
    }
}
