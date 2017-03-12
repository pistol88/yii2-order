<?php
namespace pistol88\order\logic;

use pistol88\order\interfaces\Order;

class OrderRecovery
{
    public $order;
    
    public function execute()
    {
        $this->order->setDeleted(0);
        $this->order->saveData();

        yii::createObject(['class' => CountCalculate::class, 'order' => $this->order])->execute();
        yii::createObject(['class' => CostCalculate::class, 'order' => $this->order])->execute();
        
        return true;
    }
}