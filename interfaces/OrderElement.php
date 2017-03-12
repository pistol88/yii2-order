<?php
namespace pistol88\order\interfaces;

interface OrderElement
{
    public function setOrderId($orderId);
    public function setAssigment($isAssigment);
    public function setModelName($modelName);
    public function setName($name);
    public function setItemId($itemId);
    public function setCount($count);
    public function setBasePrice($basePrice);
    public function setPrice($price);
    public function setOptions($options);
    public function setDescription($description);
    public function saveData();
}
