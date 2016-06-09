<?php
namespace pistol88\order\events;

use yii\base\Event;

class ElementEvent extends Event
{
    public $model;
    public $orderModel;
    public $productModel;
}