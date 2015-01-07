<?php
namespace app\components;

use Yii;
use yii\base\Component;
use yii\base\ArrayAccessTrait;
use yii\base\InvalidConfigException;
use app\models\Config as ConfigModel;
use yii\helpers\ArrayHelper;

/**
 * 数据库方式操作配置数据
 * @package app\components
 */
class DbConfig extends Component implements \IteratorAggregate, \ArrayAccess, \Countable
{
    use ArrayAccessTrait;

    /**
     * 数据
     * @var mixed
     */
    protected $data;

    protected $oldData;

    public function init()
    {
        $this->oldData = $this->data = $this->getData();
    }

    /**
     * 获取配置数据
     * 例: Config::get('foo.bar', 'test');
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->deepGet($key, $default);
    }

    /**
     * 设置配置数据
     * 例: Config::set('foo.bar', 'test');
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        if (is_array($key)) {
            foreach ($key as $innerKey => $innerValue) {
                $this->deepSet($innerKey, $innerValue);
            }
        } else {
            $this->deepSet($key, $value);
        }
    }

    /**
     * 通过字符串中.符号链的方式获取配置数据
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    protected function deepGet($key, $default = null)
    {
        if (is_null($key)) {
            return $this->data;
        } elseif (isset($this->data[$key])) {
            return $this->data[$key];
        }
        $array = $this->data;
        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return $default;
            }
            $array = $array[$segment];
        }
        return $array;
    }

    /**
     * 通过字符串中.符号链的方式设置配置数据
     * @param $key
     * @param $value
     * @return mixed
     */
    protected function deepSet($key, $value)
    {
        if (is_null($key)) {
            return $this->data = $value;
        }
        $keys = explode('.', $key);
        $array = & $this->data;
        while (count($keys) > 1) {
            $key = array_shift($keys);
            if ( ! isset($array[$key]) || ! is_array($array[$key])) {
                $array[$key] = array();
            }
            $array = & $array[$key];
        }
        $array[array_shift($keys)] = $value;
    }

    //运行完保存更改数据
    public function __destruct()
    {
        $this->saveData();
    }


    /**
     * 数据表存储类
     * @var string
     */
    public $configClass = 'app\models\Config';

    /**
     * 获取数据
     * @return array|\yii\db\ActiveRecord[]
     * @throws \yii\base\InvalidConfigException
     */
    protected function getData()
    {
        if (!is_a($this->configClass, ConfigModel::className(), true)) {
            throw new InvalidConfigException("The 'configClass' property must extends from '" . ConfigModel::className() . "'");
        }
        return array_map(function($data) {
            return unserialize($data['value']);
        }, ConfigModel::find()->asArray()->indexBy('name')->all());
    }

    /**
     * 保存改动的和清除删除的数据
     */
    public function saveData()
    {
        $replaceData = $deleteKeys = [];
        foreach ($this->data as $name => $value) {
            if ($value === null) { // 删除数据
                $deleteKeys[] = $name;
            } elseif (!array_key_exists($name, $this->oldData) || $value != $this->oldData[$name]) { // 修改或新增数据
                $replaceData[$name] = [$name, serialize($value)];
            }
        }
        foreach ($this->oldData as $name => $value) {
            if ((!array_key_exists($name, $this->data) || $this->data[$name] === null) && !in_array($name, $deleteKeys)) { // 删除数据
                $deleteKeys[] = $name;
            }
        }
        if (!empty($replaceData)) {
            $db = ConfigModel::getDb();
            $sql = $db->queryBuilder->batchInsert(ConfigModel::tableName(), ['name', 'value'], $replaceData);
            $sql = 'REPLACE INTO' . substr($sql, 11); // 替换INSERT INTO 改为 REPLACE INTO 语法
            $db->createCommand($sql)->execute();
        }
        if (!empty($deleteKeys)) {
            ConfigModel::deleteAll([
                'name' => $deleteKeys
            ]);
        }
    }
}