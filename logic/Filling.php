<?php
namespace pistol88\order\logic;

use pistol88\order\interfaces\Cart;
use pistol88\order\interfaces\OrderElement;
use yii;

class Filling
{
    public $order;
    
    protected $cart;
    protected $element;

    public function __construct(Cart $cart, OrderElement $element, $config = [])
    {
        $this->cart = $cart;
        $this->element = $element;
    }
    
    public function execute()
    {
        foreach($this->cart->elements as $element) {
            $elementModel = $this->element;
            $elementModel = new $elementModel;
            
            $elementModel->setOrderId($this->order->id);
            $elementModel->setAssigment($this->order->is_assigment);
            $elementModel->setModelName($element->getModelName());
            $elementModel->setName($element->getName());
            $elementModel->setItemId($element->getItemId());
            $elementModel->setCount($element->getCount());
            $elementModel->setBasePrice($element->getPrice(false));
            $elementModel->setPrice($element->getPrice());
            $elementModel->setOptions(json_encode($element->getOptions()));
            $elementModel->setDescription('');
            $elementModel->saveData();
        }
        
        $this->cart->truncate();
        
        yii::createObject(['class' => CountCalculate::class, 'order' => $this->order])->execute();
        yii::createObject(['class' => CostCalculate::class, 'order' => $this->order])->execute();
        
        return true;
    }
}