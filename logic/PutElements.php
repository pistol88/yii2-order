<?php
namespace pistol88\order\logic;

use pistol88\order\interfaces\Cart;
use yii;

class PutElements extends \yii\base\Component
{
    public $order;
    
    protected $cart;

    public function __construct(Cart $cart, $config = [])
    {
        $this->cart = $cart;
        
        return parent::__construct($config);
    }
    
    public function execute()
    {
        foreach($this->cart->elements as $element) {
            $elementModel = yii::$container->get('pistol88\order\interfaces\Element');
            $elementModel = new $elementModel;
            
            $elementModel->order_id = $this->order->id;
            $elementModel->is_assigment = $this->order->is_assigment;
            $elementModel->model = $element->getModelName();
            $elementModel->name = $element->getName();
            $elementModel->item_id = $element->getItemId();
            $elementModel->count = $element->getCount();
            $elementModel->base_price = $element->getPrice(false);
            $elementModel->price = $element->getPrice();
            $elementModel->options = json_encode($element->getOptions());
            $elementModel->description = '';
            $elementModel->save();
        }
        
        $this->cart->truncate();
        
        yii::createObject(['class' => CountCalculate::class, 'order' => $this->order])->execute();
        yii::createObject(['class' => CostCalculate::class, 'order' => $this->order])->execute();
        
        return true;
    }
}