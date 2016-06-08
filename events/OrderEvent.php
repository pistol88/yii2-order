<?php
namespace pistol88\order\events;

use yii\base\Event;

class OrderEvent extends Event
{
    public $model;
    public $elements;
}