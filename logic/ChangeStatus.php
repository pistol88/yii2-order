<?php
namespace pistol88\order\logic;

class ChangetSatus extends \yii\base\Component
{
    public $order;
    public $status;

    public function execute()
    {
        $this->order->status = $this->status;
        $this->order->save();
        
        return true;
    }
}