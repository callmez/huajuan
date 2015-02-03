<?php
namespace app\modules\admin\components;

use Yii;
use yii\web\NotFoundHttpException;

class Controller extends \app\components\Controller
{
    public $layout = '@app/modules/admin/views/layouts/main';

    public function init()
    {
        parent::init();
        $this->checkAdmin();
    }

    public function checkAdmin()
    {
        if (!Yii::$app->getUser()->can('visitAdmin')) { // 判断是否有访问后台权限
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}