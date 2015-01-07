<?php

use yii\db\Schema;
use app\components\Migration;
use app\models\Config;

class m150106_015855_initConfigTable extends Migration
{
    public function up()
    {
        $tableName = Config::tableName();

        $this->createTable($tableName, [
            'name' => Schema::TYPE_STRING . "(64) NOT NULL COMMENT '名称'",
            'value' => Schema::TYPE_TEXT . " NOT NULL COMMENT '保存的值'",
            'PRIMARY KEY (name)'
        ], $this->tableOptions);
    }

    public function down()
    {
        $this->dropTable(Config::tableName());
    }
}
