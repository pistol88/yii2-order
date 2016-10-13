<?php
namespace pistol88\order\interfaces;

interface Stock
{
    function getAmount($productId);
	
	function outcoming($stockId, $productId, $count, $orderId = null);
}
