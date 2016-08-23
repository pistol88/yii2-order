<?php
namespace pistol88\order\widgets;

use yii\helpers\Html;
use yii\helpers\Url;
use pistol88\order\models\Order;
use yii;

class OneClick extends \yii\base\Widget
{
    
    public $view = 'oneclick/form';
    public $model = null;
    
    public function init()
    {
        \pistol88\order\assets\OneClickAsset::register($this->getView());
        
        return parent::init();
    }
    
    public function run()
    {
        $model = new Order;
        
        $view = $this->getView();
        $view->on($view::EVENT_END_BODY, function($event) use ($model) {
            echo $this->render($this->view, ['model' => $this->model, 'orderModel' => $model]);
        });
        
        return Html::a(yii::t('order', 'Fast order'), '#fastOrder'.$this->model->id, ['title' => yii::t('order', 'Fast order'), 'data-toggle' => 'modal', 'data-target' => '#fastOrder'.$this->model->id, 'class' => 'btn btn-success']);
    }
}
