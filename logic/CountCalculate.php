<?php
namespace pistol88\order\logic;

use yii;

class CountCalculate
{
    public $order;
    
    public function execute()
    {
        $this->order->count = 0;
        
        foreach($this->order->elements as $element) {
            $this->order->count += $element->count;
        }

        $this->order->save();
        
        return true;
    }
}