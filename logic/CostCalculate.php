<?php
namespace pistol88\order\logic;

use yii;

class CostCalculate extends \yii\base\Component
{
    public $order;
    
    public function execute()
    {
        $this->order->base_cost = 0;
        $this->order->cost = 0;
        
        foreach($this->order->elements as $element) {
            $this->order->base_cost += ($element->base_price*$element->count);
            $this->order->cost += ($element->price*$element->count);
        }

        $this->order->save();
        
        return true;
    }
}