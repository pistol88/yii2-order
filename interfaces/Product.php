<?php
namespace pistol88\order\interfaces;

interface Product
{
    function getCode();

    function getPrice();
    
    function getAmount();
    
    function getSellModel();
    
    function minusAmount($count);
    
    function plusAmount($count);
}
