<?php

use yii\db\Schema;
use yii\db\Migration;
use app\modules\user\models\Meta;
use app\components\MigrationTrait;

/**
 * 元数据表.用来记录主题的相关数据
 */
class m150116_022355_initMetaTable extends Migration
{
    use MigrationTrait;
    public function up()
    {
        $tableName = Meta::tableName();
        $this->createTable($tableName, [
            'id' => Schema::TYPE_PK,
            'target_id' => Schema::TYPE_INTEGER . " UNSIGNED NOT NULL DEFAULT '0' COMMENT '指向的对象ID, 标记为该对象的元数据'",
            'target_type' => Schema::TYPE_STRING . "(50) NOT NULL COMMENT '指向的对象类型, 指向对象的元数据类型'",
            'author_id' => Schema::TYPE_INTEGER . " UNSIGNED NOT NULL DEFAULT '0' COMMENT '生成该条数据的用户ID'",
            'value' => Schema::TYPE_TEXT . " NOT NULL COMMENT '记录的值'",
            'type' => Schema::TYPE_STRING . "(50) NOT NULL COMMENT '元数据类型'",
            'status' => Schema::TYPE_BOOLEAN . " NOT NULL DEFAULT '0' COMMENT '状态'",
            'created_at' => Schema::TYPE_INTEGER . " UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => Schema::TYPE_INTEGER . " UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改时间'"
        ], $this->tableOptions);
        $this->createIndex('author_id', $tableName, 'author_id');
        $this->createIndex('target', $tableName, ['target_id', 'target_type']);

    }

    public function down()
    {
        $this->dropTable(Meta::tableName());
    }
}
