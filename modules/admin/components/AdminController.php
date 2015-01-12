<?php
namespace app\modules\admin\components;

use yii\web\Controller;
use app\components\ControllerTrait;

class AdminController extends Controller
{
    use ControllerTrait;
    public $layout = '@app/modules/admin/views/layouts/main';
}