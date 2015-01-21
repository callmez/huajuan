<?php

use yii\db\Schema;
use yii\db\Migration;
use yii\helpers\Console;
use app\modules\question\models\Question;
use app\modules\question\models\Answer;

class m150116_154254_initQuestion extends Migration
{
    public function up()
    {
        if(Console::confirm('是否生成测试问题数据?', true)) {
            $this->generateFakeData(rand(20, 100));
        }

    }

    public function down()
    {
        echo "m150116_154254_initQuestion cannot be reverted.\n";

        return false;
    }

    public function generateFakeData($num)
    {
        Console::startProgress(0, 100);
        $queston = new Question();
        $answer = new Answer();
        $faker = Faker\Factory::create('zh_CN');
        for ($i = 1; $i <= $num; $i++) {
            $_question = clone $queston;
            $_question->setAttributes([
                'subject' => $faker->text(rand(10, 100)),
                'content' => $faker->text(rand(100, 2000)),
                'author_id' => 1
            ]);
            if ($_question->save()) {
                $_question->setActive();

                for ($_i = 1; $_i <= rand(1, 20); $_i++) {
                    $_answer = clone $answer;
                    $_answer->setAttributes([
                        'content' => $faker->text(rand(100, 2000)),
                        'author_id' => 1
                    ]);
                    if ($_question->addAnswer($_answer)) {
                        $_answer->setActive();
                    }
                }
            }
            Console::updateProgress($i/$num * 100, 100);
        }
        Console::endProgress();
    }
}
