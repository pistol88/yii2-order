<?php
namespace pistol88\order\interfaces;

interface Order
{
    public function setDeleted($deleted);
    public function setStatus($status);
    public function saveData();
}
