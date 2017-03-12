<?php
namespace pistol88\order\logic;

use pistol88\order\interfaces\Order;

class OrderRecovery extends \yii\base\Component
{
    public $order;
    
    public function execute()
    {
        $this->order->is_deleted = 0;
        $this->order->save();

        yii::createObject(['class' => CountCalculate::class, 'order' => $this->order])->execute();
        yii::createObject(['class' => CostCalculate::class, 'order' => $this->order])->execute();
        
        return true;
    }
}