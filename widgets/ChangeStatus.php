<?php
namespace pistol88\order\widgets;

use yii\helpers\Html;
use yii\helpers\Url;
use yii;

class ChangeStatus extends \yii\base\Widget
{
    public $model = null;

    public function init()
    {
        parent::init();

        \pistol88\order\assets\ChangeStatusAsset::register($this->getView());
        \pistol88\order\assets\CloseModalAfterChangeStatusAsset::register($this->getView());
        return true;
    }
    
    public function run()
    {
        $select = Html::dropDownList('status', $this->model->status, yii::$app->getModule('order')->orderStatuses, ['data-link' => Url::toRoute(['/order/order/update-status']), 'data-id' => $this->model->id, 'class' => 'form-control pistol88-change-order-status']);
        
        return $select;
    }
}
