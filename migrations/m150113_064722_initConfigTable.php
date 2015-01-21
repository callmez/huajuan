<?php

use yii\db\Schema;
use yii\db\Migration;
use callmez\config\adapters\DbConfig;

require Yii::getAlias('@callmez/config/migrations/m150106_015855_initConfigTable.php');

/**
 * 初始化配置表
 */
class m150113_064722_initConfigTable extends m150106_015855_initConfigTable
{
    public function up()
    {
        if ($this->isDbConfig()) {
            parent::up();
        }
    }

    public function down()
    {
        if ($this->isDbConfig()) {
            parent::down();
        }
    }

    /**
     * 是否数据库类型Config
     * @return bool
     */
    public function isDbConfig()
    {
        $components = Yii::$app->getComponents();
        $config = isset($components['config']) ? is_array($components['config']) ? $components['config']['class'] : $components['config'] : null;
        return is_a($config, DbConfig::className(), true);
    }
}
