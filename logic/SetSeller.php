<?php
namespace pistol88\order\logic;

use pistol88\order\interfaces\User;

class SetSeller
{
    public $order;
    
    protected $user;
    
    public function __construct(User $user, $config = [])
    {
        $this->user = $user;
    }
    
    public function execute()
    {
        $this->order->seller_user_id = $this->user->id;
        $this->order->save();
        
        return true;
    }
}