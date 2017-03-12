<?php
namespace pistol88\order\logic;

use pistol88\order\interfaces\Order;

class OrderCancel extends \yii\base\Component
{
    public $order;
    
    public function execute()
    {
        $this->order->is_deleted = 1;
        $this->order->save();

        return true;
    }
}