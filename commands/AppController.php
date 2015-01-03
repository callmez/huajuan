<?php
namespace app\commands;

use Yii;
use yii\helpers\Console;
use yii\console\Controller;

/**
 * 项目命令
 *
 * @package app\commands
 */
class AppController extends Controller
{
    public $defaultAction = 'install';

    /**
     * 检查当前环境是否可用
     */
    public function actionCheck($path = '@app/requirements.php')
    {
        ob_start();
        ob_implicit_flush(false);
        require Yii::getAlias($path);
        $content = ob_get_clean();

        $content = str_replace('OK', $this->ansiFormat("OK", Console::FG_GREEN), $content);
        $content = str_replace('WARNING!!!', $this->ansiFormat("WARNING!!!", Console::FG_YELLOW), $content);
        $content = str_replace('FAILED!!!', $this->ansiFormat("FAILED!!!", Console::FG_RED), $content);
        echo $content;
    }

    /**
     * 项目安装 当代码第一次初始化后执行此命令可引导安装项目
     */
    public function actionInstall()
    {
        $lockFile = Yii::getAlias('@app/install.lock');
        if (!file_exists($lockFile)) {
            $result = $this->runSteps([
                '数据库配置' => 'db',
                '初始化数据库数据' => 'migrate'
            ]);
            if ($result) {
                echo $this->ansiFormat("恭喜, 站点配置成功!\n", Console::FG_GREEN);
                touch($lockFile);
            }
        } else {
            echo "站点已经配置完毕,无需再配置\n(如需重新配置, 请删除{$lockFile}文件后再执行命令!)\n";
        }
    }

    public function runSteps(array $steps)
    {
        $i = 1;
        foreach ($steps as $step => $args) {
            echo "\n\n - Step {$i} {$step} \n";
            echo "==================================================\n";
            !is_array($args) && $args = (array)$args;
            $method = array_shift($args);
            $result = call_user_func_array([$this, 'action' . $method], $args);
            if ($result === false) {
                echo $this->ansiFormat("{$step}失败, 退出安装流程\n", Console::FG_RED);
                return false;
            }
            $i++;
        }
        return true;
    }

    /**
     * 生成数据库配置文件
     * @return mixed
     */
    public function actionDb()
    {
        $dbFile = Yii::getAlias('@app/config/db.php');
        if (!file_exists($dbFile)) {
            echo $this->ansiFormat("默认数据库配置文件未找到,将进入数据库配置创建流程\n", Console::FG_RED);
            return $this->generateDbFile($dbFile);
        }
        echo "'{$dbFile}' 配置文件已存在, 无需配置\n";
    }

    /**
     * 创建数据库配置文件
     * @param $dbFile
     * @return mixed
     */
    public function generateDbFile($dbFile)
    {
        $host = $this->prompt('请输入数据库主机地址:', [
            'default' => 'localhost'
        ]);
        $dbName = $this->prompt('请输入数据库名称(如果为空将使用账号作为数据库名称):');
        $dbConfig = [
            'dsn' => "mysql:host={$host};dbname={$dbName}",
            'username' => $this->prompt("请输入数据库访问账号:", [
                'default' => 'root'
            ]),
            'password' => $this->prompt("请输入数据库访问密码:"),
            'tablePrefix' => $this->prompt("请输入数据库表前缀:", [
                'default' => 'tbl_'
            ]),
            'charset' => $this->prompt("请输入数据默认的字符集:", [
                'default' => 'utf8'
            ])
        ];
        if (empty($dbName)) {
            $dbConfig['dsn'] .= $dbConfig['username'];
        }

        $message = null;
        if ($this->confirm('是否测试数据库可用?', true)) {

            $db = Yii::createObject(array_merge([
                'class' => 'yii\db\Connection'
            ], $dbConfig));

            try {
                $db->open();

                echo $this->ansiFormat("数据连接成功\n", Console::FG_GREEN);
            } catch (\Exception $e) {
                echo $this->ansiFormat("数据连接失败:" . $e->getMessage() . "\n", Console::FG_RED);
                $message = '依然写入文件?';
            }
        }

        if ($message === null || $this->confirm($message)) {
            echo "生成数据库配置文件...\n";
            $code = <<<EOF
<?php
return [
    'class' => 'yii\db\Connection',
    'dsn' => '{$dbConfig['dsn']}',
    'username' => '{$dbConfig['username']}',
    'password' => '{$dbConfig['password']}',
    'tablePrefix' => '{$dbConfig['tablePrefix']}',
    'charset' => '{$dbConfig['charset']}',
];
EOF;
            file_put_contents($dbFile, $code);
            echo $this->ansiFormat("恭喜! 数据库配置完毕!\n", Console::FG_GREEN);
        } elseif($this->confirm("是否重新设置?", false)) {
            return $this->generateDbFile($dbFile);
        } else {
            return false;
        }
    }

    /**
     * 生成数据库结构和数据
     */
    public function actionMigrate()
    {

    }
}