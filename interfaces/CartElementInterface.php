<?php
namespace pistol88\order\models\tools;

interface CartElementInterface
{
    public function getCartName();
    
    public function getCartPrice();
}
