<?php
namespace pistol88\order\logic;

use pistol88\order\interfaces\Order;

class OrderCancel
{
    public $order;
    
    public function execute()
    {
        $this->order->setDeleted(1);
        $this->order->saveData();

        return true;
    }
}