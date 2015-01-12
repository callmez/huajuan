<?php
namespace tests\codeception\unit\components;

use Yii;
use yii\codeception\TestCase;
use Codeception\Specify;

/**
 * Config组件测试
 * @package tests\codeception\unit\components
 */
class ConfigTest extends TestCase
{
    use Specify;

    /**
     * 测试Config功能
     */
    public function testConfig()
    {
        if (!Yii::$app->has('config')) {
            return ;
        }
        $config = Yii::$app->config;

        $config->set('test', 'test');
        $this->assertEquals('test', $config->get('test'));

        $config->set('test.test', 'test');
        $this->assertEquals('test', $config->get('test.test'));

        $config->set('test', null);
        $this->assertEquals(null, $config->get('test'));

    }
}