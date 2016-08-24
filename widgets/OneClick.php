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
    public $content = null;
    
    public function init()
    {
        if(is_null($this->content)) {
            $this->content = yii::t('order', 'Fast order');
        }
        
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
        
        return Html::a($this->content, '#fastOrder'.$this->model->id, ['title' => yii::t('order', 'Fast order'), 'data-toggle' => 'modal', 'data-target' => '#fastOrder'.$this->model->id, 'class' => 'btn btn-success']);
    }
}
