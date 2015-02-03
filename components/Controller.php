<?php
namespace app\components;

use Yii;
use yii\helpers\Url;
use yii\web\Response;

class Controller extends \yii\web\Controller
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
        if ($resultType === null) {
            $resultType = Yii::$app->getRequest()->getIsAjax() ? 'json' : 'html';
        } elseif ($resultType === 'flash') {
            $resultType = Yii::$app->getRequest()->getIsAjax() ? 'json' : $resultType;
        }
        $data = [
            'type' => $type,
            'message' => $message,
            'redirect' => Url::to($redirect)
        ];
        switch ($resultType) {
            case 'html':
                return $this->render($this->messageLayout, $data);
            case 'json':
                Yii::$app->getResponse()->format = Response::FORMAT_JSON;
                return $data;
            case 'flash':
                Yii::$app->session->setFlash($type, $message);
                if ($redirect !== null)  {
                    Yii::$app->end(0, $this->redirect($data['redirect']));
                }
                return true;

            default:
                return $message;
        }
    }
}