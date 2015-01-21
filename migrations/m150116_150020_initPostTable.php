<?php

use yii\db\Schema;
use yii\db\Migration;
use app\models\Post;

class m150116_150020_initPostTable extends Migration
{
    public function up()
    {
        $tableName = Post::tableName();
        $this->createTable($tableName, [
            'id' => Schema::TYPE_PK,
            'pid' => Schema::TYPE_INTEGER . " UNSIGNED NOT NULL DEFAULT '0' COMMENT '所属话题ID.默认为0, 表示为主题,否则为评论'",
            'author_id' => Schema::TYPE_INTEGER . " UNSIGNED NOT NULL DEFAULT '0' COMMENT '作者ID'",
            'subject' => Schema::TYPE_STRING . " NOT NULL DEFAULT '' COMMENT '主题'",
            'content' => Schema::TYPE_TEXT . " NOT NULL COMMENT '主题内容'",
            'view_count' => Schema::TYPE_INTEGER . " UNSIGNED NOT NULL DEFAULT '0' COMMENT '查看数'",
            'comment_count' => Schema::TYPE_INTEGER . " UNSIGNED NOT NULL DEFAULT '0' COMMENT '评论数'",
            'favorite_count' => Schema::TYPE_INTEGER . " UNSIGNED NOT NULL DEFAULT '0' COMMENT '收藏数'",
            'like_count' => Schema::TYPE_INTEGER . " UNSIGNED NOT NULL DEFAULT '0' COMMENT '喜欢数'",
            'hate_count' => Schema::TYPE_INTEGER . " UNSIGNED NOT NULL DEFAULT '0' COMMENT '讨厌数'",
            'type' =>  Schema::TYPE_STRING . "(50) NOT NULL COMMENT '类型(如问答模块的:question, answer)'",
            'status' => Schema::TYPE_BOOLEAN . " NOT NULL DEFAULT '0' COMMENT 'status'",
            'created_at' => Schema::TYPE_INTEGER . " UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => Schema::TYPE_INTEGER . " UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改时间'",
        ]);
        $this->createIndex('author_id', $tableName, 'author_id');
    }

    public function down()
    {
        $this->dropTable(Post::tableName());
    }
}
