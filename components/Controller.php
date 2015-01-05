<?php
namespace app\components;

use Yii;
use yii\helpers\Url;
use yii\web\Response;

class Controller extends \yii\console\Controller
{
    public $messageLayout = '//common/message';
    /**
     * 发送消息
     * @param $message
     * @param string $type
     * @param null $redirect
     * @param null $resultType
     * @return array|bool|string
     */
    public function message($message, $type = 'error', $redirect = null, $resultType = null)
    {
        $resultType === null && $resultType = Yii::$app->getRequest()->getIsAjax() ? 'json' : 'html';
        $redirect = Url::to($redirect);
        $data = [
            'type' => $type,
            'message' => $message,
            'redirect' => $redirect
        ];

        switch ($resultType) {
            case 'html':
                return $this->render($this->messageLayout, $data);
            case 'json':
                Yii::$app->getResponse()->format = Response::FORMAT_JSON;
                return $data;
            default:
                return $message;
        }
    }

    /**
     * 发送flash消息
     * @param $message
     * @param string $type
     * @param null $redirect
     * @return bool
     */
    public function flash($message, $type = 'error', $redirect = null) {
        Yii::$app->session->setFlash($type, $message);
        if ($redirect) {
            Yii::$app->getResponse()->redirect($redirect);
            Yii::$app->end();
        }
        return true;
    }
}