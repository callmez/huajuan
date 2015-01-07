<?php
namespace app\models;

use yii\db\ActiveRecord;
use yii\base\ArrayAccessTrait;

/**
 * 系统设置存储表
 * @package app\models
 */
class Config extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%config}}';
    }
}