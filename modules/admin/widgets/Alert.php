<?php
namespace app\modules\admin\widgets;

class Alert extends \app\widgets\Alert
{
    public $alertIcons = [
        'error'   => '<i class="fa fa-ban"></i>',
        'danger'  => '<i class="fa fa-ban"></i>',
        'success' => '<i class="fa fa-check"></i>',
        'info'    => '<i class="fa fa-info"></i>',
        'warning' => '<i class="fa fa-warning"></i>'
    ];

    protected function alert($message, $type)
    {
        $message = $this->alertIcons[$type] . $message;
        return parent::alert($message, $type);
    }
}