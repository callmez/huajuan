<?php

use yii\db\Schema;
use yii\db\Migration;
use app\modules\tag\models\Tag;
use app\modules\tag\models\TagItem;

class m150116_044243_initTagTable extends Migration
{
    public function up()
    {
        //标签表
        $tableName = Tag::tableName();
        $this->createTable($tableName, [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . "(64) NOT NULL COMMENT '标签名'",
            'icon' => Schema::TYPE_STRING . " NOT NULL DEFAULT '' COMMENT '标签图标'",
            'description' => Schema::TYPE_TEXT . " NOT NULL COMMENT '版块介绍'",
            'author_id' => Schema::TYPE_INTEGER . " UNSIGNED NOT NULL DEFAULT '0' COMMENT '作者'",
            'status' => Schema::TYPE_BOOLEAN . " NOT NULL DEFAULT '0' COMMENT '标签状态'",
            'created_at' => Schema::TYPE_INTEGER . " UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => Schema::TYPE_INTEGER . " UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改时间'",
        ]);
        $this->createIndex('name', $tableName, 'name', true);
        $this->createIndex('author_id', $tableName, 'author_id');

        //标签数据表
        $tableName = TagItem::tableName();
        $this->createTable($tableName, [
            'id' => Schema::TYPE_PK,
            'tid' => Schema::TYPE_STRING . "(64) NOT NULL COMMENT '标签id'",
            'target_id' => Schema::TYPE_INTEGER . " UNSIGNED NOT NULL DEFAULT '0' COMMENT '目标id'",
            'target_type' => Schema::TYPE_STRING . "(100) NOT NULL DEFAULT '' COMMENT '目标类型'",
            'created_at' => Schema::TYPE_INTEGER . " UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间'"
        ]);
        $this->createIndex('item', $tableName, ['tid', 'target_id', 'target_type'], true);
        $this->createIndex('target_type', $tableName, ['target_type', 'target_id']);
    }

    public function down()
    {
        $this->dropTable(Tag::tableName());
        $this->dropTable(TagItem::tableName());
    }
}
